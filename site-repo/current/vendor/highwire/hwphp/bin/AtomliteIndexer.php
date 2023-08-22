<?php

namespace HighWire\bin;

use HighWire\Clients\ClientFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Provider\Console\Command;
use HighWire\Clients\A4dExtract\A4dExtract;
use HighWire\Clients\AtomLiteReprocessor\AtomLiteReprocessor;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Index apaths in atomlite
 */
class AtomliteIndexer extends Command {

  /**
   * {@inheritDoc}
   */
  protected function configure() {
    $this
      ->setName('atomlite:indexer')
      ->setDescription('Index atoms in atomlite')
      ->addArgument('apaths', InputArgument::OPTIONAL, 'Comma separated list of apaths')
      ->addOption('index-children', 'ic', InputOption::VALUE_NONE, 'Add this flag to index all children of the given paths')
      ->addOption('environment', 'env', InputOption::VALUE_OPTIONAL, 'The service environment to use. Can be development, qa, or production.', 'production');
  }

  /**
   * {@inheritDoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $env = $input->getOption('environment');
    $index_children = $input->getOption('index-children');
    $apaths = $input->getArgument('apaths');
    if (!empty($apaths)) {
      $apaths = explode(",", $apaths);
      foreach ($apaths as &$apath) {
        $apath = trim($apath);
      }
    }

    if (empty($apaths)) {
      while ($line = fgets(STDIN)) {
        $apaths[] = trim($line);
      }
    }

    $indexer = ClientFactory::get('atom-lite-reprocessor', ['guzzle-config' => ['timeout' => 300]], $env);
    $atom = ClientFactory::get('atom', ['guzzle-config' => ['timeout' => 300]], $env);

    if ($index_children) {
      $output->writeln("Getting children...");
      foreach ($apaths as $apath) {
        $pattern = str_replace(".atom", "/*.atom", $apath);
        $child_apaths = $atom->pathsFromPattern($pattern);
        if (!empty($child_apaths)) {
          $apaths = array_merge($apaths, $child_apaths);
        }
      }
    }

    $output->writeln("Indexing " . count($apaths) . " apaths using '$env' service environment.");

    // Asynchronously index 50 paths at a time.
    $chunks = array_chunk($apaths, 50);
    $failed_paths = [];
    $total_finished = 0;
    foreach ($chunks as $chunk) {
      $index_promises = [];
      foreach ($chunk as $apath) {
        $index_promises[$apath] = $indexer->indexApathAsync($apath);
      }
      foreach ($index_promises as $apath => $promise) {
        try {
          $promise->wait();
        }
        catch (\Exception $e) {
          $failed_paths[] = $apath;
        }
      }

      $total_finished = $total_finished + count($chunk);
      $percent_done = intval(($total_finished / count($apaths)) * 100);
      $output->writeln("Finished indexing " . $total_finished . "/" . count($apaths) . " apaths $percent_done% done");
      if (!empty($failed_paths)) {
        $output->writeln("Failed indexing " . count($failed_paths));
      }
    }

    if (!empty($failed_paths)) {
      $output->writeln("Failed indexing " . count($failed_paths));
      foreach ($failed_paths as $apath) {
        $output->writeln($apath);
      }
    }

  }

}
