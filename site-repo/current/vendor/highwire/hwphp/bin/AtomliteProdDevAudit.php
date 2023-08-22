<?php

namespace HighWire\bin;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Provider\Console\Command;
use HighWire\Clients\ClientFactory;

/**
 * Compare atomlite ids in prod vs dev
 */
class AtomliteProdDevAudit extends Command {

  /**
   * {@inheritDoc}
   */
  protected function configure() {
    $this
      ->setName('atomlite:prod-dev-audit')
      ->setDescription('Audit apaths in atomlite prod against apaths atomlite dev')
      ->addArgument('policy-name', InputArgument::REQUIRED, 'The policy name. Ex. item-bits.')
      ->addArgument('corpus', InputArgument::REQUIRED, 'The corpus.');

  }
  /**
   * {@inheritDoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $policy_name = $input->getArgument('policy-name');
    $corpus = $input->getArgument('corpus');
    $atomlite_prod = ClientFactory::get('atom-lite', ['guzzle-config' => ['timeout' => 300]], 'production');
    $atomlite_dev = ClientFactory::get('atom-lite', ['guzzle-config' => ['timeout' => 300]], 'development');
    $prod_ids = $atomlite_prod->getCorpusIds($corpus, $policy_name);
    $dev_ids = $atomlite_dev->getCorpusIds($corpus, $policy_name);


    $output->writeln("Comparing apaths in atomlite prod with atomlite dev for Policy - '$policy_name' and Corpus - '$corpus'");
    $output->writeln("Found " . count($prod_ids) . " apaths in atomlite production");
    $output->writeln("Found " . count($dev_ids) . " apaths in atomlite development");
    $paths_missing_in_dev = array_diff($prod_ids, $dev_ids);
    if (!empty($paths_missing_in_dev)) {
      $output->writeln("");
      $output->writeln("Found " . count($paths_missing_in_dev) . " apaths in prod that do not exist in dev");
      foreach ($paths_missing_in_dev as $apath) {
        $output->writeln($apath);
      }
    }
  }

}
