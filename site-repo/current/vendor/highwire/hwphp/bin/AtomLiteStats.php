<?php

namespace HighWire\bin;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Provider\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use HighWire\Clients\ClientFactory;


/**
 * Get stats on atomlite payloads.
 */
class AtomLiteStats extends Command {

  /**
   * {@inheritDoc}
   */
  protected function configure() {
    $this
      ->setName('atomlite:stats')
      ->setDescription('Get stats about indexed data in atomlite for a given policy')
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

    $ids = $atomlite->getCorpusIds($corpus, $policy_name);
    if (empty($ids)) {
      $output->writeln("No apaths found for policy $policy_name and corpus $corpus");
      return;
    }

    $missing = [];
    $stats = [];
    $output->writeln("Pulling stats for Policy - '$policy_name' and Corpus - '$corpus'");
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

      $payloads = $atomlite->getMultiple($chunk, $policy_name);
      foreach ($payloads as $payload) {
        if (empty($payload)) {
          $missing[] = $apath;
          continue;
        }

        if (empty($payload['item-type'])) {
          continue;
        }

        if (empty($stats[$payload['item-type']])) {
          $stats[$payload['item-type']] = [
            'count' => 0,
          ];
        }

        $stats[$payload['item-type']]['count']++;
      }

      $total_checked = $total_checked + count($chunk);
    }

    if (empty($stats)) {
      $output->writeln("No stats found");
      return;
    }

    foreach ($stats as $item_type => $data) {
      $output->writeln($item_type);
      $output->writeln("Count: " . $data['count']);
    }

    if (!empty($missing)) {
      $output->writeln("Found missing payloads in atomlite:");
      foreach ($missing as $id) {
        $output->writeln($id);
      }
    }
  }

}
