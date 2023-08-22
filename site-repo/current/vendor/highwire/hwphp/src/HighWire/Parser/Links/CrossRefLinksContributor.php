<?php

namespace HighWire\Parser\Links;

use HighWire\Parser\DOMElementBase;

/**
 * Article holds a single "<contributor>" element from links.highwire.org/crossref/.
 * 
 * Example:
 * <contributor first-author="true" sequence="first" contributor_role="author">
 *   <given_name>R.</given_name>
 *   <surname>Colisson</surname>
 * </contributor>
 */
class CrossRefLinksContributor extends DOMElementBase {

  /**
   * Get the role
   * 
   * @return string|null
   *   The role as defined in the 'contributor_role' attribute.
   */
  public function role() {
    return $this->getAttribute('contributor_role');
  }

  /**
   * Get the contributor given name
   * 
   * @return string|null
   *   The given name as defined in the 'given_name' element.
   */
  public function given_name() {
    $elem = $this->xpathSingle("./qrs:given_name");
    if ($elem) {
      return $elem->nodeValue;
    }
  }

  /**
   * Get the contributor surname
   * 
   * @return string|null
   *   The surname as defined in the 'surname' element.
   */
  public function surname() {
    $elem = $this->xpathSingle("./qrs:surname");
    if ($elem) {
      return $elem->nodeValue;
    }
  }

}
