<?php

namespace HighWire\Parser\Links;

use BetterDOMDocument\DOMDoc;
use HighWire\Clients\ResponseTrait;
use HighWire\Clients\HWResponseInterface;

/**
 * crossref links for a single atom resource
 *
 * @link https://sites.google.com/a/highwire.org/systems/home/documentation/links/crossref
 */
class CrossRefLinks extends DOMDoc implements HWResponseInterface, \Iterator, \Countable {

  use ResponseTrait;

  /**
   * Current position of the iterator.
   *
   * @var int
   */
  private $position = 0;

  /**
   * {@inheritdoc}
   */
  public $ns = [
    'atom' => 'http://www.w3.org/2005/Atom',
    'qrs' => 'http://www.crossref.org/qrschema/2.0',
  ];

  /**
   * {@inheritdoc}
   */
  public function getData() {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function rewind() {
    $this->position = 0;
  }

  /**
   * {@inheritdoc}
   */
  public function current() {
    $elem = $this->xpathSingle('(//qrs:forward_link/*)[' . ($this->position + 1) . ']');
    if (!empty($elem)) {
      return new CrossRefLinksCitation($elem, $this);
    }
    else {
      return NULL;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function key() {
    return $this->position;
  }

  /**
   * {@inheritdoc}
   */
  public function next() {
    $this->position++;
  }

  /**
   * {@inheritdoc}
   */
  public function count() {
    $links = $this->xpath('//qrs:forward_link/*');
    if ($links) {
      return count($links);
    }
    else {
      return 0;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function valid() {
    $elem = $this->xpathSingle('(//qrs:forward_link/*)[' . ($this->position + 1) . ']');
    return !empty($elem);
  }

}
