<?php

namespace HighWire\Parser\Toc;

/**
 * Toc Group holds a collection of information about a toc section.
 *
 * @code
 * <map:group xml:id="Themes">
 *   <map:grouping-key>Themes</map:grouping-key>
 *   <map:display-name>Themes</map:display-name>
 *   <map:display-name location="toc">Themes</map:display-name>
 *   <map:citations>
 *     <map:citation-ref sass-href="/ajpcell/310/11/C955.atom" base="http://sass.highwire.org" legacy-resource-id="ajpcell;310/11/C955" has-gca="yes"/>
 *   </map:citations>
 * </map:group>
 * @endcode
 */
class Group extends TocElementBase {

  /**
   * @param string $parent
   *   The parent group header-id.
   *
   * @return mixed
   *   Returns an array representation of a map:group / toc section.
   */
  public function parseTocGroups(string $parent = '') {
    $toc_section = [];
    $toc_section['heading'] = $this->displayName();
    $toc_section['groupingkey'] = $this->groupingKey();
    $toc_section['header-id'] = $this->headerId();
    $toc_section['parent'] = $parent;

    $apath = $this->apath();
    if (!empty($apath)) {
      $toc_section['apath'] = $apath;
    }

    $toc_blurb = $this->tocBlurb();
    if (!empty($toc_blurb)) {
      $toc_section['toc-blurb'] = $toc_blurb;
    }

    $pdf = $this->pdf();
    if (!empty($pdf)) {
      $toc_section['pdf'] = $pdf;
    }

    $url = $this->url();
    if (!empty($url)) {
      $toc_section['url'] = $url;
    }

    // Get the items for the section.
    $toc_section['items'] = $this->items();



    $search_params = $this->searchParameters();
    foreach ($search_params as $search_param) {
      $search_parameter = new SearchParameters($search_param, $this->dom);
      $toc_section['search-parameters'] = $search_parameter->parseSearchParameters();
    }

    return $toc_section;
  }

  /**
   * Get the items for the toc group.
   *
   * @return array
   *   An array representation of the toc group children.
   */
  public function items() {
    $items = [];
    $children = $this->citations();
    foreach ($children as $child) {
      $item = FALSE;
      if ($child->nodeName == 'map:citations') {
        $citation = new Citations($child, $this->dom);
        $item = $citation->parseCitations($this->headerId());
      }
      elseif ($child->nodeName == 'map:group') {
        $group = new Group($child, $this->dom);
        $item[$group->headerId()] = $group->parseTocGroups($this->headerId());
      }

      if (!empty($item)) {
        $items = array_merge($items, $item);
      }

    }

    return $items;

  }

  /**
   * Get the section grouping key.
   *
   * @return string
   *   The grouping-key for the section.
   */
  public function groupingKey(): string {
    $groupingKey = '';
    if ($item = $this->xpathSingle('./map:grouping-key')) {
      $groupingKey = $item->nodeValue;
    }
    return $groupingKey;
  }

  /**
   * Get the section display name.
   *
   * @return string
   *   The display name for the section.
   */
  public function displayName(): string {
    $displayName = '';

    if ($item = $this->xpathSingle('./map:display-name[@location="toc"]')) {
      $displayName = $item->nodeValue;
    }

    return $displayName;
  }

  /**
   * Gets the header id for a section.
   *
   * @return string
   *   An empty string if the id was not found.
   */
  public function headerId(): string {
    $header_id = $this->xpathSingle('./@xml:id');

    if (!empty($header_id)) {
      return $header_id->nodeValue;
    }

    return '';
  }

  /**
   * The toc blurb for a section.
   *
   * @return array
   *   An array of blurbs. If no blurbs exist, return an empty array.
   */
  public function tocBlurb(): array {
    $blurb_elems = $this->xpath('./map:toc-blurb');
    $toc_blurb = [];

    if ($blurb_elems->length > 0) {
      foreach ($blurb_elems as $elem) {
        $toc_blurb[] = $this->dom->innerText($elem);
      }
    }

    return $toc_blurb;
  }

  /**
   * Get the citation elements.
   *
   * @return mixed
   *   Return map:citations DOMElements or FALSE.
   */
  public function citations() {
    return $this->xpath('./map:citations | ./map:group');
  }

  /**
   * Get the citation elements.
   *
   * @return mixed
   *   Return map:citations DOMElements or FALSE.
   */
  public function searchParameters() {
    return $this->xpath('./map:search-parameters');
  }

  /**
   * Get the section pdf.
   *
   * @return string
   *   A url to a pdf, or empty string if not found.
   */
  public function pdf() {
    $pdf = '';
    $resource_elems = $this->xpath("./map:resource");
    if ($resource_elems->length > 0) {
      $resource_elem = $resource_elems->item(0);
      if ($path = $resource_elem->getAttribute('sass-href')) {
        $pdf = $resource_elem->getAttribute('base') . $path;
      }
    }

    return $pdf;
  }

  /**
   * Get the map:url for the group.
   *
   * @return string
   *   The string value for <map:url>, or an empty string if not found.
   */
  public function url() {
    $url = '';
    $map_url = $this->xpathSingle('./map:url');

    if (!empty($map_url)) {
      $url = $this->dom->innerText($map_url);
    }

    return $url;

  }

  /**
   * Get the apath for the group.
   *  This is set as the sass-href attribute on the map:group
   * @return string
   *   The string value for sass-href attribute, or an empty string if not found.
   */
  public function apath() {
    $apath = $this->getAttribute('sass-href');

    return $apath;

  }

}
