<?php

namespace HighWire\Parser\Links;

use HighWire\Clients\ResponseTrait;
use HighWire\Clients\HWResponseInterface;

/**
 * Scopus links for a single atom resource
 *
 * @link https://sites.google.com/a/highwire.org/systems/home/documentation/links
 */
class ScopusLinks extends ISILinks implements HWResponseInterface {

  use ResponseTrait;

  /**
   * {@inheritdoc}
   */
  public $ns = [
    'atom' => 'http://www.w3.org/2005/Atom',
    'timescited' => 'http://schema.highwire.org/Scopus/Links',
  ];

}
