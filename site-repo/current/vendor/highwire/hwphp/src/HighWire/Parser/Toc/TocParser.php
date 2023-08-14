<?php

namespace HighWire\Parser\Toc;

use BetterDOMDocument\DOMDoc;

/**
 * The toc parser is a wrapping class for the parsing the toc, providing
 * methods for access to the toc structure.
 */
class TocParser extends DOMDoc {

  /**
   * The toc document.
   *
   * BetterDOMDocument\DOMDoc
   */
  protected $toc;

  /**
   * Constructor.
   */
  public function __construct(string $xml) {
    $this->toc = new DOMDoc($xml);
  }

  /**
   * Getter for the toc property.
   *
   * @return \BetterDOMDocument\DOMDoc
   *   Toc DOMDoc.
   */
  public function toc() {
    return $this->toc;
  }

  /**
   * Get all apaths from a toc.
   *
   * @return array
   *   An array of apaths.
   */
  public function getAllIds() {
    $apaths = [];
    $ids = $this->toc->xpath('//@sass-href');

    foreach ($ids as $id) {
      $apaths[] = $id->value;
    }

    return $apaths;
  }


  /**
   * Get the toc structure.
   *
   * @return array
   *   An array of toc sections keyed by header id.
   */
  public function getTocStructure() {
    // Get the nodes
    $map_groups = $this->toc->xpath('./map:group | ./map:citations | ./map:citation-ref');

    $toc_structure = [];
    foreach ($map_groups as $map_group) {
      // Handle top level citation-ref items that are not in a <map:group>.
      // Such as front and back-matter items.
      if ($map_group->nodeName == 'map:citation-ref') {
        $citation = new Citation($map_group);
        $toc_structure[]['apath'] = $citation->apath();
      }

      if ($map_group->nodeName == 'map:group') {
        $group = new Group($map_group, $this->toc);
        $toc_section = $group->parseTocGroups();

        // Add the section to the ToC structure, keyed by header id.
        // If an id doesn't exist, default to numeric so items don't get overridden.
        $key = $group->headerId();
        if (!empty($key)) {
          $toc_structure[$group->headerId()] = $toc_section;
        }
        else {
          $toc_structure[] = $toc_section;
        }
      }

      // Handle top level citations that are not in a <map:group>.
      if ($map_group->nodeName == 'map:citations') {
        $items = new Citations($map_group, $this->toc);
        $toc_structure[]['items'] = $items->parseCitations();
      }
    }

    return $toc_structure;
  }

  /**
   * Get a flattened toc with citations in the order they appear in the toc.
   *
   * @return array
   *   An array of apaths in toc order, or an empty array if no citations were found.
   */
  public function getTocFlat() {
    $toc = $this->getTocStructure();

    $toc_items = [];
    foreach ($toc as $section) {
      if (!empty($section['items'])) {
        $items = $this->getSectionItems($section['items']);
        foreach ($items as $item) {
          $toc_items[] = $item;
        }
      }
    }

    return $toc_items;
  }

  /**
   * @param array $section_items
   *   A toc section items array.
   *
   * @return array
   *   An array of apaths for the toc section, and all children in toc order.
   */
  public function getSectionItems(array $section_items) {
    $citations = [];
    if (!empty($section_items)) {
      foreach ($section_items as $item) {
        if (is_string($item)) {
          $citations[] = $item;
        }
        if (is_array($item) && !empty($item['items'])) {
          $items = $this->getSectionItems($item['items']);
          $citations = array_merge($citations, $items);
        }
      }
    }

    return $citations;
  }

  /**
   * @param string $id
   *   The header id of the section to return.
   * @return mixed
   *   A DOMElement representing a section, or FALSE if the id wasn't found.
   */
  public function sectionById(string $id) {
    return $this->toc->xpathSingle("//map:group[@xml:id='" . $id . "']");
  }

  /**
   * Get the FULL flattened toc with citations in the order they appear in the toc.
   *
   * @param int $max_depth
   *   The maximum depth to traverse the TOC document.
   *
   * @return array
   *   An array of apaths in toc order, or an empty array if no citations were found.
   */
  public function getFullTocFlat(int $max_depth = 0) {
    $toc = $this->getTocStructure();

    $toc_items = [];
    foreach ($toc as $section) {
      if (!empty($section['apath'])) {
        $toc_items[] = $section['apath'];
      }
      if (!empty($section['items']) && $max_depth > 0) {
        $items = $this->getFullSectionItems($section['items'], $max_depth);
        if (!empty($items)) {
          foreach ($items as $item) {
            $toc_items[] = $item;
          }
        }
      }
    }

    return $toc_items;
  }

  /**
   * @param array $section_items
   *   A toc section items array.
   * @param int $max_depth
   *   The maximum depth to traverse the TOC document.
   * @param int $step
   *   The recusion number for setting max depth.
   *
   * @return array
   *   An array of apaths for the toc section, and all children in toc order.
   */
  public function getFullSectionItems(array $section_items, int $max_depth = 0, int $step = 0) {
    $full_section = [];

    if ($max_depth != 0 && $max_depth == $step) {
      return [];
    }

    if (!empty($section_items)) {
      // Increment our step count.
      $step++;
      foreach ($section_items as $item) {
        if (is_string($item) && substr($item, -5) === ".atom") {
          $full_section[] = $item;
        }
        if (!empty($item['apath'])) {
          $full_section[] = $item['apath'];
        }
        if (is_array($item) && !empty($item['items'])) {
          $items = $this->getFullSectionItems($item['items'], $max_depth, $step);
          if (!empty($items)) {
            foreach ($items as $another_item) {
              $full_section[] = $another_item;
            }
          }
        }
      }
    }

    return $full_section;
  }

}
