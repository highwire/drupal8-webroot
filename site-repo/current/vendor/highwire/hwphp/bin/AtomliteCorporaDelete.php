<?php

namespace HighWire\bin;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Provider\Console\Command;
use HighWire\Clients\ClientFactory;
use Symfony\Component\Console\Input\InputOption;

/**
 * Delete ids and payloads from atomlite.
 */
class AtomliteCorporaDelete extends Command {

  /**
   * {@inheritDoc}
   */
  protected function configure() {
    $this
      ->setName('atomlite:corpus-delete')
      ->setDescription('Delete a corpus/corpora from atomlite')
      ->addOption('environment', 'env', InputOption::VALUE_OPTIONAL, 'The service environment to use. Can be development, qa, or production.', 'production')
      ->addArgument('corpora', InputArgument::REQUIRED, 'A comma separated list of corpora.');
  }

  /**
   * {@inheritDoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $corpora = explode(',', $input->getArgument('corpora'));
    foreach ($corpora as &$corpus) {
      $corpus = trim($corpus);
    }
    $env = $input->getOption('environment');
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['timeout' => 300]], $env);
    $policies = $atomlite->getPolices();
    $corpus_policies = [];
    foreach ($corpora as $corpus) {
      foreach ($policies as $policy) {
        if (!empty($policy['corpusList']) && in_array($corpus, $policy['corpusList'])) {
          $corpus_policies[$corpus][] = $policy['mimeTypes'][0];
        }
      }
    }

    if (empty($corpus_policies)) {
      $output->writeln("No policies found for corpora " . implode(", ", $corpora));
      return;
    }
    $ids = [];
    foreach ($corpus_policies as $corpus => $policies) {
      foreach ($policies as $policy_mime_type) {
        if ($new_ids = $atomlite->getCorpusIds($corpus, $policy_mime_type)) {
          $ids = array_merge($ids, $new_ids);
          $output->writeln("Found " . count($ids) . " for policy $policy_mime_type");
        }
      }
    }

    if (!empty($ids)) {
      $ids = array_unique($ids);
      $output->writeln("Found " . count($ids) . ' unique apaths for ' . implode(", ", $corpora));
      $output->writeln("Starting delete operations...");
      $chunked_ids = array_chunk($ids, 50);
      $total_deleted = 0;
      foreach ($chunked_ids as $chunk) {
        $promises = [];
        foreach ($chunk as $id) {
          $promises[] = $atomlite->deleteIdAsync($id);
        }

        foreach ($promises as $key => $promise) {
          try {
            $promise->wait();
            unset($promises[$key]);
          }
          catch (\Exception $e) {
            $output->writeln("An error occured trying to delete $id");
            unset($promises[$key]);
            continue;
          }
        }

        $total_deleted += count($chunk);
        $output->writeln("Deleted " . $total_deleted . " out of " . count($ids));
      }
    }
    else {
      $output->writeln("No apaths found for " . implode(", ", $corpora));
    }
  }

}
