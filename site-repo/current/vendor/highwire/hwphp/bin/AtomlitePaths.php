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
 * Fetch apaths in atomlite
 */
class AtomlitePaths extends Command {

  /**
   * {@inheritDoc}
   */
  protected function configure() {
    $this
      ->setName('atomlite:get-paths')
      ->setDescription('Fetch atoms in atomlite')
      ->addArgument('apaths', InputArgument::REQUIRED, 'Comma separated list of apaths')
      ->addOption('environment', 'env', InputOption::VALUE_OPTIONAL, 'The service environment to use. Can be development, qa, or production.', 'production');
  }

  /**
   * {@inheritDoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $env = $input->getOption('environment');
    $apaths = explode(",", $input->getArgument('apaths'));
    foreach ($apaths as &$apath) {
      $apath = trim($apath);
    }

    $atom = ClientFactory::get('atom', ['guzzle-config' => ['timeout' => 300]], $env);

    foreach ($apaths as $apath) {
      $pattern = str_replace(".atom", "/*.atom", $apath);
      $child_apaths = $atom->pathsFromPattern($pattern);
      if (!empty($child_apaths)) {
        $apaths = array_merge($apaths, $child_apaths);
      }
    }

    // Print output to command line.
    $output->writeln($apaths);

  }

}
