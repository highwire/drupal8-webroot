<?php

namespace HighWire\bin;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Provider\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use HighWire\Clients\ClientFactory;


/**
 * Check for payloads that exist in atomlite but not sass.
 */
class AtomliteSassAudit extends Command {

  /**
   * {@inheritDoc}
   */
  protected function configure() {
    $this
      ->setName('atomlite-sass:audit')
      ->setDescription('Audit apaths in atomlite against apaths in sass for given corpus and poilyc')
      ->addArgument('policy-name', InputArgument::REQUIRED, 'The policy name. Ex. item-bits.')
      ->addArgument('corpus', InputArgument::REQUIRED, 'The corpus.')
      ->addOption('environment', 'env', InputOption::VALUE_OPTIONAL, 'The service environment to use. Can be development, qa, or production.', 'production');

  }
  /**
   * {@inheritDoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $policy_name = $input->getArgument('policy-name');
    $corpus = $input->getArgument('corpus');
    $env = $input->getOption('environment');
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['timeout' => 300]], $env);
    $sass = ClientFactory::get('sass', ['guzzle-config' => ['timeout' => 300]], $env);

    $ids = $atomlite->getCorpusIds($corpus, $policy_name);

    if (empty($ids)) {
      $output->writeln("No apaths found in atomlite for policy $policy_name and corpus $corpus");
      return;
    }

    $missing = [];
    $stats = [];
    $output->writeln("Comparing apaths in atomlite with what's in sass for Policy - '$policy_name' and Corpus - '$corpus' in the $env environment");
    $output->writeln("Found " . count($ids) . " apaths in atomlite");

    $chunks = array_chunk($ids, 100);
    $total_checked = 0;
    $last = 0;
    $start_time = time();
    foreach ($chunks as $chunk) {
      $per_done = intval(($total_checked / count($ids)) * 100);

      if ($last != $per_done && $per_done > 0) {
        $last = $per_done;
        $total_time = time() - $start_time;
        $output->writeln("Progress $per_done%, total time $total_time seconds, total checked $total_checked");
      }

      $promises = [];
      foreach ($chunk as $path) {
        $promises[$path] = $sass->headResourceAsync($path);
      }

      foreach ($promises as $apath => $promise) {
        try {
          $promise->wait();
        }
        catch (\Exception $e) {
          $missing[] = $apath;
        }
      }

      $total_checked = $total_checked + count($chunk);
    }

    if (!empty($missing)) {
      $output->writeln("Payloads in atomlite that are not in SASS:");
      foreach ($missing as $id) {
        $output->writeln($id);
      }
    }
    else {
      $output->writeln("No payloads in atomlite that don't exist in sass.");
    }
  }

}
