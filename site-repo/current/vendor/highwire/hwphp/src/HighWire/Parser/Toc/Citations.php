<?php

namespace HighWire\Parser\Toc;

/**
 * Citations is a representative of <map:citations>.
 * <map:citations> children can be <map:group> or <map:citation-ref>.
 *
 * For example:
 *  @code
 *  <map:citations>
 *    <map:citation-ref sass-href="/btr/1/1-2/14.atom" base="http://sass-dev.highwire.org" legacy-resource-id="btr;1/1-2/14" has-gca="yes"/>
 *      <map:group xml:id="PRE-CLINICALRESEARCHEditorialComment-1">
 *      <map:grouping-key>PRE-CLINICAL RESEARCH/Editorial Comment</map:grouping-key>
 *      <map:display-name>Editorial Comment</map:display-name>
 *      <map:display-name location="toc">Editorial Comment</map:display-name>
 *      <map:citations>
 *        <map:citation-ref sass-href="/btr/1/1-2/29.atom" base="http://sass-dev.highwire.org" legacy-resource-id="btr;1/1-2/29"/>
 *      </map:citations>
 *    </map:group>
 *  </map:citations>
 *  @endcode
 */
class Citations extends TocElementBase {

  /**
   * @param string $parent
   *   The parent map:group id for the citations.
   *
   * @return array
   *   An array of nested toc sections and apaths.
   */
  public function parseCitations($parent = '') {
    $items = [];

    $children = $this->xpath('./map:citation-ref | ./map:group');
    foreach ($children as $child) {

      // We have a citation item, add it to the array.
      if ($child->tagName == 'map:citation-ref') {
        $citation = new Citation($child);
        $items[] = $citation->apath();
      }
      // We have a nested section, recurse.
      elseif ($child->tagName == 'map:group') {
        $section = new Group($child, $this->dom);
        $items[$section->headerId()] = $section->parseTocGroups($parent);
      }
    }

    return $items;
  }

}
