<?php

namespace HighWire\Parser\Links;

use BetterDOMDocument\DOMDoc;
use HighWire\Clients\ResponseTrait;
use HighWire\Clients\HWResponseInterface;

/**
 * inter-journal links for a single resource
 *
 * @link https://sites.google.com/a/highwire.org/systems/home/documentation/links/ijlink
 */
class IJLinks extends DOMDoc implements HWResponseInterface {

  use ResponseTrait;

  /**
   * {@inheritdoc}
   */
  public $ns = [
    'atom' => 'http://www.w3.org/2005/Atom',
    'nlm' => 'http://schema.highwire.org/NLM/Journal',
    'hwp' => 'http://schema.highwire.org/Journal',
    'c' => 'http://schema.highwire.org/Compound'
  ];

  /**
   * {@inheritdoc}
   */
  public function getData() {
    return $this;
  }

  /**
   * Get an inter-journal link that will provide free access.
   *
   * @param string $variant
   *   Variant such as "full-text", "abstract", "table-of-contents" etc. Defaults to "default".
   * 
   * @param string $type
   *   The variant mime-time. Usually (and defauled to) "application/xhtml+xml". Another popular one is "application/pdf".
   * 
   * @return string|null
   *   The inter-journal link, granting free access.
   */
  public function getLink($variant = 'default', $type = "application/xhtml+xml") {
    $elem = $this->xpathSingle("//atom:link[@c:role='http://schema.highwire.org/variant/$variant'][@type='$type']");
    if (empty($elem)) {
      return NULL;
    }
    return $elem->getAttribute('href');
  }

}
