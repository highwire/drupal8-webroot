<?php

namespace HighWire\Parser\Toc;

/**
 * SearchParameters is a representative of <map:search-parameters>.
 *
 * For example:
 *  @code
 *  <map:search-parameters>
 *    <map:param name="tocsectionid">Retinopathy%20and%20Visual%20Dysfunction</map:param>
 *    <map:param name="volume">8</map:param>
 *    <map:param name="issue">17</map:param>
 *  </map:search-parameters>
 *  @endcode
 */
class SearchParameters extends TocElementBase {

  /**
   * Get the search parameters for the section.
   *
   * @return array
   *   An array of search parameters.
   */
  public function parseSearchParameters() {
    $items = [];
    $children = $this->xpath('./map:param');
    foreach ($children as $child) {
      if ($name = $child->getAttribute('name')) {
        if ($text = $this->dom->innerText($child)) {
          $items[$name] = $text;
        }
      }
    }

    return $items;
  }

}
