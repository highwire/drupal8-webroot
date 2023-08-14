<?php

namespace HighWire\Parser\Taxonomy;

use BetterDOMDocument\DOMDoc;
use HighWire\Clients\ResponseTrait;
use HighWire\Clients\HWResponseInterface;

/**
 * TaxonomyTreeList represents a list of taxonomy stubs. 
 * 
 * For example:
 *  @code
 *  <taxonomies xmlns="http://schema.highwire.org/Taxonomy" xmlns:acs="http://schema.highwire.org/AtomCollection" xmlns:n="http://schema.highwire.org/Taxonomy/Node" baseUri="http://taxonomy-svc-dev.highwire.org/tree/springer.xml">
 *    <taxonomy description="springer content subjects taxonomy" id="1" name="Subjects" publisher="springer" collection="content" scheme="subject"/>
 *    <taxonomy description="springer georef category taxonomy" id="3" name="GeoRef" publisher="springer" collection="georef" scheme="category"/>
 *  </taxonomies>
 *  @endcode
 */
class TaxonomyTreeList extends DOMDoc implements \Iterator, \Countable, HWResponseInterface {

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
    'xhtml' => 'http://www.w3.org/1999/xhtml',
    'taxonomy' => 'http://schema.highwire.org/Taxonomy',
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
    $elem = $this->xpathSingle('(//taxonomy:taxonomy)[' . ($this->position + 1) . ']');
    if (!empty($elem)) {
      return new TaxonomyTree($elem);
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
    $elem = $this->xpathSingle('(//taxonomy:taxonomy)[' . ($this->position + 1) . ']');
    return !empty($elem);
  }

  /**
   * {@inheritdoc}
   */
  public function count() {
    return count($this->xpath('//taxonomy:taxonomy'));
  }

  /**
   * Get all taxonomy tree stubs.
   *
   * @return \HighWire\Parser\Taxonomy\TaxonomyTree[]
   *   Array of TaxonomyTree objects.
   */
  public function getTaxonomyTrees() {
    $elems = $this->xpath("//taxonomy:taxonomy");
    if (empty($elems)) {
      return [];
    }
    $trees = [];
    foreach ($elems as $elem) {
      $trees[] = new TaxonomyTree($elem);
    }
    return $trees;
  }

}
