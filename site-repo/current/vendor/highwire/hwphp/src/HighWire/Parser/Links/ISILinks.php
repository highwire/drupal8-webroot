<?php

namespace HighWire\Parser\Links;

use BetterDOMDocument\DOMDoc;
use HighWire\Clients\ResponseTrait;
use HighWire\Clients\HWResponseInterface;

/**
 * ISI Links for a single atom resource
 *
 * @link https://sites.google.com/a/highwire.org/systems/home/documentation/links/isi
 */
class ISILinks extends DOMDoc implements HWResponseInterface {

  use ResponseTrait;

  /**
   * {@inheritdoc}
   */
  public $ns = [
    'atom' => 'http://www.w3.org/2005/Atom',
    'timescited' => 'http://schema.highwire.org/Isi/Links',
  ];

  /**
   * {@inheritdoc}
   */
  public function getData() {
    return $this;
  }

  /**
   * Get the source, citing, or related link for ISI.
   *
   * @param string $type
   *   Type of link to get. Can be 'source', 'citing', or 'related'.
   * 
   * @return string|null
   *   The link URL to ISI.
   */
  public function getLink($type = 'citing') {
    $elem = $this->xpathSingle("//atom:link[@rel='$type']");
    if (empty($elem)) {
      return NULL;
    }
    return $elem->getAttribute('href');
  }

  /**
   * Get the number of times cited by ISI
   * 
   * @return int
   *   Number of times cited by ISI
   */
  public function timescited(): int {
    $elem = $this->xpathSingle("//timescited:count");
    if (empty($elem)) {
      return 0;
    }
    return intval($elem->nodeValue);
  }

}
