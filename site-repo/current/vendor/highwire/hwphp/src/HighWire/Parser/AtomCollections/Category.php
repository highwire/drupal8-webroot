<?php

namespace HighWire\Parser\AtomCollections;

use HighWire\Parser\DOMElementBase;

/**
 * Category holds a single "<atom:category>" element.
 * 
 * For example:
 *  @code
 *  <atom:category term="b60006c4" scheme="subject" acs:rank="2"/>
 *  @endcode
 */
class Category extends DOMElementBase {
  
  /**
   * Get the term id.
   *
   * @return string
   *   The term id as defined in the 'term' attribute.
   */
  public function term(): string {
    return $this->getAttribute('term');
  }

  /**
   * Get the scheme.
   *
   * @return string
   *   The scheme as defined in the 'scheme' attribute.
   */
  public function scheme(): string {
    return $this->getAttribute('scheme');
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
   * Get the rank.
   *
   * @return int
   *   The rank as defined in the 'acs:rank' attribute.
   */
  public function rank(): int {
    return intval($this->getAttribute('acs:rank'));
  }

  /**
   * Get the taxonomy node id.
   *
   * @return string
   *   The taxonomy node id as defined in the 'tax:node_id' attribute.
   */
  public function taxonomyNodeId(): string {
    return $this->getAttribute('tax:node_id');
  }

  /**
   * Get the taxonomy depth.
   *
   * @return int
   *   The taxonomy node depth as defined in the 'tax:depth' attribute.
   */
  public function taxonomyNodeDepth(): int {
    return intval($this->getAttribute('tax:depth'));
  }

  /**
   * Get the description.
   *
   * @return string
   *   HTML description defined by the <description> element.
   */
  public function description(): string {
    $description = $this->xpathSingle('./acs:description/acs:div');
    if ($description) {
      return $this->dom->saveHTML($description);
    }
    else {
      return '';
    }
  }


  /**
   * Get the apath for the membership subject
   *
   * @return string|false
   *   Apath of the mebership subject, or FALSE if cannot be determined.
   */
  public function apath() {
    return $this->dom->apath();
  }

}
