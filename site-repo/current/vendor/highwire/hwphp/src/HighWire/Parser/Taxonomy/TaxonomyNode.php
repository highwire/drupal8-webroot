<?php

namespace HighWire\Parser\Taxonomy;

use HighWire\Parser\DOMElementBase;

/**
 * TaxonomyNode holds a single taxonomy "<node>" element, which may have more <node> elements nested as children.
 *
 * For example:
 *  @code
 *  <node id="702fd6d9" path="counseling" term="Counseling" label="Counseling">
 *    <description>&lt;div&gt;Description of the &lt;em&gt;Counseling&lt;/em&gt; node.&lt;/div&gt;</description>
 *    <node id="1f94cfc5" path="general-counseling" term="General Counseling" label="General Counseling">
 *      <description>&lt;div&gt;Description of the child node.&lt;/div&gt;</description>
 *    </node>
 *  </node>
 *  @endcode
 */
class TaxonomyNode extends DOMElementBase {

  /**
   * Get the id.
   *
   * @return string
   *   The id as defined in the 'id' attribute.
   */
  public function id(): string {
    return $this->getAttribute('id');
  }

  /**
   * Get the publisher-assigned term id.
   *
   * @return string
   *   The id as defined in the 'pub_term_id' attribute.
   */
  public function pubTermId(): string {
    return $this->getAttribute('pub_term_id');
  }

  /**
   * Get the term name.
   *
   * @return string
   *   The term name as defined in the 'term' attribute.
   */
  public function term(): string {
    return $this->getAttribute('term');
  }

  /**
   * Get the path.
   *
   * @return string
   *   The path as defined in the 'path' attribute.
   */
  public function path(): string {
    return $this->getAttribute('path');
  }

  /**
   * Get the label.
   *
   * @return string
   *   The label as defined in the 'label' attribute.
   */
  public function label(): string {
    return $this->getAttribute('label');
  }

  /**
   * Get the publisher.
   *
   * @note only works if it's the root node.
   *
   * @return string
   *   The label as defined in the 'publisher' attribute.
   */
  public function publisher(): string {
    return $this->getAttribute('publisher');
  }

  /**
   * Get the collection.
   *
   * @note only works if it's the root node.
   *
   * @return string
   *   The label as defined in the 'collection' attribute.
   */
  public function collection(): string {
    return $this->getAttribute('collection');
  }

  /**
   * Get the order.
   *
   * @return string
   *   The label as defined in the 'order' attribute.
   */
  public function order(): string {
    return $this->getAttribute('order');
  }

  /**
   * Get the scheme.
   *
   * @note only works if it's the root node.
   *
   * @return string
   *   The label as defined in the 'scheme' attribute.
   */
  public function scheme(): string {
    return $this->getAttribute('scheme');
  }

  /**
   * Get the depth.
   *
   * The root node has a depth of 0.
   *
   * @return int
   *   The depth as defined in the 'depth' attribute.
   */
  public function depth(): int {
    return $this->calculateDepth($this->elem);
  }

  /**
   * Calculate the depth of this node.
   *
   * This is temporary fix until the taxonomy service adds back the depth attribute.
   *
   * @param mixed $element
   *   The dom element to calcuate the depth of.
   * @param int $depth
   *   The starting depth.
   *
   * @return int
   *   The depth of the node.
   */
  protected function calculateDepth($element, $depth = 0) {
    if (empty($element->parentNode)) {
      return $depth;
    }

    if ($element->parentNode->nodeName != "node") {
      return $this->calculateDepth($element->parentNode, $depth);
    }

    $depth++;
    return $this->calculateDepth($element->parentNode, $depth);
  }

  /**
   * Get the Atom Collections Service Term ID.
   *
   * @return string
   *   The acs term id as defined in the 'acs:term' attribute.
   */
  public function ACSTermID(): string {
    return $this->getAttribute('acs:term');
  }

  /**
   * Get posidtion of this node in relation to it's siblings.
   *
   * Position starts at 0.
   *
   * @return int
   *   The node's order / position in relation to siblings.
   */
  public function position(): int {
    $prev_siblings = $this->xpath('./preceding-sibling::taxonomy:node');
    if (empty($prev_siblings)) {
      return 0;
    }
    return $prev_siblings->count();
  }

  /**
   * Get weight of this node in relation to all nodes in tree.
   *
   * Weight starts at 0.
   *
   * @return int
   *   The node's order / weight in relation to all nodes in the tree.
   */
  public function weight(): int {
    $prev = $this->xpath('./preceding::taxonomy:node|./ancestor::taxonomy:node');
    if (empty($prev)) {
      return 0;
    }
    return $prev->count();
  }

  /**
   * Get the parent.
   *
   * @return string
   *   The parent node id. Will return '' if the node is the root.
   */
  public function parent(): string {
    $id = $this->xpathSingle('../@id');
    if (empty($id)) {
      return '';
    }
    else {
      return $id->nodeValue;
    }
  }

  /**
   * Get the parent TaxonomyNode.
   *
   * @return \HighWire\Parser\Taxonomy\TaxonomyNode
   *   The parent node. Will return NULL if the node is the root.
   */
  public function parentNode() {
    $parent = $this->parent();
    if (empty($parent)) {
      return NULL;
    }
    return $this->dom->getNode($parent);
  }

  /**
   * Get the children.
   *
   * @return string[]
   *   An array of child IDs.
   */
  public function children(): array {
    $ids = $this->xpath('./taxonomy:node/@id');

    if (empty($ids)) {
      return [];
    }

    $children = [];
    foreach ($ids as $id) {
      $children[] = $id->nodeValue;
    }

    return $children;
  }

  /**
   * Get the children as an array of TaxonomyNode.
   *
   * @return HighWire\Parser\Taxonomy\TaxonomyNode[]
   *   An array of child TaxonomyNode.
   */
  public function childNodes(): array {
    $children = $this->children();

    $nodes = [];
    foreach ($children as $id) {
      $nodes[$id] = $this->dom->getNode($id);
    }

    return $nodes;
  }

  /**
   * Get the description.
   *
   * @return string
   *   The description as unnamespaced HTML.
   */
  public function description(): string {
    $html = $this->xpathSingle('./taxonomy:description/text()');
    if (empty($html)) {
      return '';
    }
    return html_entity_decode($html->nodeValue);
  }

}
