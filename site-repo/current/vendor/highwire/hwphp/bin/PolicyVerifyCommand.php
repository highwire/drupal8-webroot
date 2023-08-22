<?php

namespace HighWire\bin;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Provider\Console\Command;
use HighWire\Parser\ExtractPolicy\ExtractPolicy;

/**
 * Verify an extract policy.
 */
class PolicyVerifyCommand extends Command {

  /**
   * {@inheritDoc}
   */
  protected function configure() {
    $this
      ->setName('policy:verify')
      ->setDescription('Verify an extract policy')
      ->addArgument('policy-file', InputArgument::REQUIRED, 'The policy file to verify. Accepts a file path or a URL.');
  }

  /**
   * {@inheritDoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $file = $input->getArgument('policy-file');

    $xml = file_get_contents($file);
    $policy = new ExtractPolicy($xml);

    $policy->verify();

    // Try to use go-highwire to verify as well
    if (!empty(shell_exec('which highwire'))) {
      $status = 0;
      passthru(sprintf("highwire policy-verify %s", escapeshellarg($file)), $status);
      if ($status != 0) {
        exit($status);
      }
    }
    else {
      error_log("warning: Could not verify with go-highwire `highwire policy-verify` command. For additional verification please install highwire command from https://github.com/highwire/go-highwire");
    }
  }

}
