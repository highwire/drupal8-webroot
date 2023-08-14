<?php

namespace HighWire\bin;

use HighWire\Clients\ClientFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Provider\Console\Command;

/**
 * Audit atom collection against atomlite paylaods
 */
class AtomCollectionAudit extends Command {

  /**
   * {@inheritDoc}
   */
  protected function configure() {
    $this
      ->setName('atom-collections:audit')
      ->setDescription('Audit atom collection against atomlite paylaods')
      ->addArgument('corpora', InputArgument::REQUIRED, 'Comma separated list of corpora')
      ->addArgument('publisher', InputArgument::REQUIRED, 'The atom collections publisher string')
      ->addArgument('policy', InputArgument::REQUIRED, 'Extract policy name')
      ->addOption('scheme', 'sch', InputOption::VALUE_OPTIONAL, 'The atom collections scheme', 'subject')
      ->addOption('workspace', 'wksp', InputOption::VALUE_OPTIONAL, 'The atom collections workspace.', 'content')
      ->addOption('environment', 'env', InputOption::VALUE_OPTIONAL, 'The service environment to use. Can be development, qa, or production.', 'production')
      ->addOption('taxonomy-field', 'tf', InputOption::VALUE_OPTIONAL, 'The extract field that hold the taxonomy field data', 'taxonomy-terms');

  }

  /**
   * {@inheritDoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $env = $input->getOption('environment');
    $workspace = $input->getOption('workspace');
    $scheme = $input->getOption('scheme');
    $publisher = $input->getArgument('publisher');
    $policy = $input->getArgument('policy');
    $taxonomy_field = $input->getOption('taxonomy-field');
    $corpora = explode(',', $input->getArgument('corpora'));

    foreach ($corpora as &$corpus) {
      $corpus = trim($corpus);
    }

    $output->writeln("Auditing atom collection against atomlite for corpora '" . $input->getArgument('corpora') . "', publisher '$publisher', policy '$policy', scheme '$scheme', workspace '$workspace' and environment '$env'");

    $atom_col = ClientFactory::get('atom-collections', ['guzzle-config' => ['timeout' => 300]], $env);
    $taxonomy = ClientFactory::get('taxonomy', ['guzzle-config' => ['timeout' => 300]], $env);
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['timeout' => 300]], $env);
    $atomreprocess = ClientFactory::get('atom-lite-reprocessor', ['guzzle-config' => ['timeout' => 300]], $env);
    $sass = ClientFactory::get('sass', ['guzzle-config' => ['timeout' => 300]], $env);

    $tree = $taxonomy->getTree($publisher, $workspace, $scheme);
    $ids = $tree->getNodeIds();
    // Remove top level term, we don't extract it and don't use it.
    array_shift($ids);

    $missing = FALSE;
    $missing_apaths = [];
    $missing_from_sass = [];

    foreach ($ids as $term_id) {
      $term = $tree->getNodeByACSId($term_id);
      if (empty($term)) {
        $output->writeln("Could not find term with id $term_id");
        continue;
      }
      $term_label = $term->label();
      $term_label = "'$term_label'";
      $output->writeln("Getting members for term $term_label - $term_id");
      $results = $atom_col->getTermMembership($term_id, $publisher, $scheme, $workspace, 10000);
      $apaths = $results->apaths();
      $output->writeln("Found " . count($apaths) . " members for $term_label - $term_id");
      $promises = [];
      foreach ($apaths as $apath) {
        $interested_corpus = FALSE;
        foreach ($corpora as $corpus) {
          if (strpos($apath, $corpus) !== FALSE) {
            $interested_corpus = TRUE;
            break;
          }
        }

        foreach ($corpora as $corpus) {
          if ($apath == "/$corpus.atom") {
            $interested_corpus = FALSE;
            break;
          }
        }

        if (!$interested_corpus) {
          continue;
        }

        $missing = FALSE;

        try {
          $payload = $atomlite->get($apath, $policy);
        }
        catch (\Exception $e) {
          try {
            $sass->headResource($apath);
            $output->writeln("Payload missing from atomlite, but exists in sass $apath");
            $missing = TRUE;
          }
          catch (\Exception $e) {
            $output->writeln("Apath in atom collection that doesn't exist in sass $apath");
            $missing_from_sass[$term_id][] = $apath;
            continue;
          }
        }

        if (!empty($payload[$taxonomy_field])) {
          $found = FALSE;
          foreach ($payload[$taxonomy_field] as $t) {
            if (!empty($t['id']) && $t['id'] == $term_id) {
              $found = TRUE;
              break;
            }
            elseif (empty($t['id'])) {
              $output->writeln("Found missing term id for payload $apath");
              break;
            }
          }
          if (!$found) {
            $output->writeln("Found missing term $term_label - $term_id  for apath $apath");
            $missing = TRUE;
          }
        }
        else {
          $output->writeln("Found missing term $term_label - $term_id for apath $apath");
          $missing = TRUE;
        }

        if ($missing) {
          $output->writeln("Reindexing $apath");
          $promises[] = $atomreprocess->indexApathAsync($apath);
          $missing_apaths[$term_id][] = $apath;
        }
      }
      if (!empty($promises)) {
        $output->writeln("Resolving index promises");
        foreach ($promises as $promise) {
          $promise->wait();
        }
      }
    }

    $output->writeln("");
    $output->writeln("");
    if (!empty($missing_apaths)) {
      $output->writeln("Atomlite payloads that are missing collections collection apaths");
      foreach ($missing_apaths as $term_id => $apaths) {
        $term = $tree->getNodeByACSId($term_id);
        $term_label = $term->label();
        $term_label = "'$term_label'";
        $output->writeln("Found " . count($apaths) . " apaths missing from atomlite for $term_label - $term_id");
        foreach ($apaths as $apath) {
          $output->writeln("$apath");
        }
      }
    }

    $output->writeln("");
    $output->writeln("");

    if (!empty($missing_from_sass)) {
      $output->writeln("Missing from sass");
      foreach ($missing_from_sass as $term_id => $apaths) {
        $term = $tree->getNodeByACSId($term_id);
        $term_label = $term->label();
        $term_label = "'$term_label'";
        $output->writeln("Found " . count($apaths) . " apaths in collection $term_label - $term_id that don't exist in sass");
        foreach ($apaths as $apath) {
          $output->writeln("$apath");
        }
      }
    }
  }

}
