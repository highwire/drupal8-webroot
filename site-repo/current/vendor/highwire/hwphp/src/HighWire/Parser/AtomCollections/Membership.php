<?php

namespace HighWire\Parser\AtomCollections;

use BetterDOMDocument\DOMDoc;
use HighWire\Clients\ResponseTrait;

/**
  * Membership parses a membership XML document that provides collections membership for a given apath.
  *
  * For example:
  *   http://atom-collections-alpha.highwire.org/springer/content/atom?member=/freebird/book/978-1-6170-5275-0.atom
  */
class Membership extends DOMDoc implements \Iterator, \Countable {

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
    'acs' => 'http://schema.highwire.org/AtomCollection',
    'tax' => 'http://schema.highwire.org/Taxonomy',
    'atom' => 'http://www.w3.org/2005/Atom',
  ];

  /**
   * Get the apath for the membership subject
   *
   * @return string|false
   *   Apath of the mebership subject, or FALSE if cannot be determined.
   */
  public function apath() {
    $elem = $this->xpathSingle("//atom:link[@rel='proxy']");
    if (empty($elem)) {
      return FALSE;
    }
    return $elem->getAttribute('href');
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
    $elem = $this->xpathSingle('(//atom:category)[' . ($this->position + 1) . ']');
    if (!empty($elem)) {
      return new Category($elem, $this);
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
  public function valid() {
    $elem = $this->xpathSingle('(//atom:category)[' . ($this->position + 1) . ']');
    return !empty($elem);
  }

  /**
   * {@inheritdoc}
   */
  public function count() {
    $categories = $this->xpath('//atom:category');
    if ($categories) {
      return count($categories);
    }
    else {
      return 0;
    }
  }

  /**
   * Get all membership categories.
   *
   * @return \HighWire\Parser\AtomCollections\Category[]
   *   Array of Category objects.
   */
  public function getCategories() {
    $elems = $this->xpath('//atom:category');
    if (empty($elems)) {
      return [];
    }
    $nodes = [];
    foreach ($elems as $elem) {
      $nodes[] = new Category($elem, $this);
    }
    return $nodes;
  }

}
