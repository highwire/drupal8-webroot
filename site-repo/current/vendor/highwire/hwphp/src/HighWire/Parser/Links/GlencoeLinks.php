<?php

namespace HighWire\Parser\Links;

use BetterDOMDocument\DOMDoc;
use HighWire\Clients\ResponseTrait;
use HighWire\Clients\HWResponseInterface;

/**
 * Glencoe Links for a single atom resource
 * 
 * NOTE THAT THIS CLASS IS CURRENTLY A STUB. PLEASE EXPAND AS REQUIRED.
 *
 * @link https://sites.google.com/a/highwire.org/systems/home/documentation/links
 * @link http://confluence.highwire.org/display/AppSupport/Application+Service+Support+-+Home?preview=%2F2099136%2F3375112%2FLINKSSERVICE.pptx
 */
class GlencoeLinks extends DOMDoc implements HWResponseInterface {

  use ResponseTrait;

  /**
   * {@inheritdoc}
   */
  public $ns = [
    'atom' => 'http://www.w3.org/2005/Atom',
  ];

  /**
   * {@inheritdoc}
   */
  public function getData() {
    return $this;
  }

}
