<?php

namespace HighWire\Parser\Taxonomy;

use BetterDOMDocument\DOMDoc;
use HighWire\Clients\ResponseTrait;
use HighWire\Clients\HWResponseInterface;

/**
 * TaxonomyTree is a single taxonomy.
 *
 * It may be comprised of just a stub (a <taxonomy> element without children),
 * or it may contain an entire tree (with <node> children).
 *
 * For example:
 *  @code
 *  <taxonomy xmlns="http://schema.highwire.org/Taxonomy" description="springer content subjects taxonomy" id="1" name="Subjects" publisher="springer" collection="content" scheme="subject">
 *    <node id="e3b97379" term="Subjects" label="Subjects" acs:term="e3b97379" path="subjects" >
 *      ... decendant node elements elided ...
 *    </node>
 *  </taxonomy>
 *  @endcode
 */
class TaxonomyTree extends DOMDoc implements \Iterator, \Countable, HWResponseInterface {

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
    $elem = $this->xpathSingle('(//taxonomy:node)[' . ($this->position + 1) . ']');
    if (!empty($elem)) {
      return new TaxonomyNode($elem, $this);
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
    $elem = $this->xpathSingle('(//taxonomy:node)[' . ($this->position + 1) . ']');
    return !empty($elem);
  }

  /**
   * {@inheritdoc}
   */
  public function count() {
    return count($this->xpath('//taxonomy:node'));
  }

  /**
   * Get the publishser.
   *
   * @return string
   *   The publisher as defined in the 'publisher' attribute.
   */
  public function publisher(): string {
    $publisher = $this->xpathSingle('@publisher')->nodeValue;
    return $publisher ? $publisher : '';
  }

  /**
   * Get the collection.
   *
   * @return string
   *   The collection as defined in the 'collection' attribute.
   */
  public function collection(): string {
    $collection = $this->xpathSingle('@collection')->nodeValue;
    return $collection ? $collection : '';
  }

  /**
   * Get the scheme.
   *
   * @return string
   *   The scheme as defined in the 'scheme' attribute.
   */
  public function scheme(): string {
    $scheme = $this->xpathSingle('@scheme')->nodeValue;
    return $scheme ? $scheme : '';
  }

  /**
   * Get the human readable name.
   *
   * @return string
   *   The name as defined in the 'name' attribute.
   */
  public function name(): string {
    $name = $this->xpathSingle('@name')->nodeValue;
    return $name ? $name : '';
  }

  /**
   * Get the human readable description.
   *
   * @return string
   *   The description as defined in the 'description' attribute.
   */
  public function description(): string {
    $desc = $this->xpathSingle('@description')->nodeValue;
    return $desc ? $desc : '';
  }

  /**
   * Get a taxonomy node by ID.
   *
   * @param string $id
   *   The taxonomy node ID.
   *
   * @return \HighWire\Parser\Taxonomy\TaxonomyNode|null
   *   The Taxnomy Node, or NULL if no node with that ID exists.
   */
  public function getNode(string $id) {
    $elem = $this->xpathSingle("//taxonomy:node[@id='$id']");
    if (empty($elem)) {
      return NULL;
    }
    else {
      return new TaxonomyNode($elem, $this);
    }
  }

  /**
   * Get a taxonomy node by Atom Collections Service Term ID.
   *
   * @param string $acs_term_id
   *   The ACS term id.
   *
   * @return \HighWire\Parser\Taxonomy\TaxonomyNode|null
   *   The Taxnomy Node, or NULL if no node with that ACS Term ID exists.
   */
  public function getNodeByACSId(string $acs_term_id) {
    $elem = $this->xpathSingle("//taxonomy:node[@acs:term='$acs_term_id']");
    if (empty($elem)) {
      return NULL;
    }
    else {
      return new TaxonomyNode($elem, $this);
    }
  }

  /**
   * Get the first level of tax nodes.
   *
   * @return \HighWire\Parser\Taxonomy\TaxonomyNode[]
   *   An array of taxonomy node objects.
   */
  public function getBaseNodes(): array {
    $elems = $this->xpath("./taxonomy:node/taxonomy:node");

    if (empty($elems)) {
      return [];
    }

    $nodes = [];

    foreach ($elems as $elem) {
      $node = new TaxonomyNode($elem, $this);
      $nodes[$node->id()] = $node;
    }

    return $nodes;
  }

  /**
   * Get all taxonomy nodes.
   *
   * @return \HighWire\Parser\Taxonomy\TaxonomyNode[]
   *   Array of TaxonomyNode keyed by id.
   */
  public function getNodes(): array {
    $elems = $this->xpath("//taxonomy:node");
    if (empty($elems)) {
      return [];
    }
    $nodes = [];
    foreach ($elems as $elem) {
      $node = new TaxonomyNode($elem, $this);
      $nodes[$node->id()] = $node;
    }
    return $nodes;
  }

  /**
   * Get all taxonomy node IDs.
   *
   * @return string[]
   *   Array of taxonomy node ids.
   */
  public function getNodeIds() {
    $elems = $this->xpath("//taxonomy:node");
    if (empty($elems)) {
      return [];
    }

    $ids = [];
    foreach ($elems as $elem) {
      $ids[] = $elem->getAttribute('id');
    }

    return $ids;
  }

  /**
   * Get the root node.
   *
   * @return \HighWire\Parser\Taxonomy\TaxonomyNode|null
   *   The root node, or NULL if there is no root node.
   */
  public function rootNode() {
    $elem = $this->xpathSingle("./taxonomy:node");
    if (empty($elem)) {
      return NULL;
    }
    return new TaxonomyNode($elem, $this);
  }

  /**
   * Check if this taxonomy tree is stub, not the whole tree.
   *
   * @return bool
   *   Returns TRUE if tree is a stub, FALSE if it's the whole tree.
   */
  public function isStub() {
    return $this->rootNode() == NULL;
  }

  /**
   * Get the parents.
   *
   * @param string $node_id
   *   The node id to get the parent ids for. Note this
   *   is NOT a drupal node id.
   *
   * @return array
   *   The parents node ids.
   */
  public function getParents($node_id): array {
    $ids = [];

    $items = $this->xpath('//taxonomy:node[@id="' . $node_id . '"]');
    foreach ($items as $item) {
      if ($parent_id = $item->parentNode->getAttribute('id')) {
        $ids[] = $parent_id;
      }
    }
    return $ids;
  }

  /**
   * Get the related terms.
   *
   * @param string $node_id
   *   The node id to get the parent ids for. Note this
   *   is NOT a drupal node id.
   *
   * @return array
   *   The related term node ids.
   */
  public function getRelatedTerms($node_id): array {
    $related_terms = [];

    // Check if this particular term has related terms
    $elems = $this->xpath("//taxonomy:node[@id='$node_id']/taxonomy:relatednodes");

    foreach ($elems as $elem) {
      $related_terms = [];
      $related_nodes = $elem->getElementsByTagName("relatednode");
      foreach ($related_nodes as $related_node) {
        $related_terms[] = $related_node->getAttribute('id');
      }
    }

    return $related_terms;
  }

}
