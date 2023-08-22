<?php

namespace HighWire\Parser\Toc;

/**
 * A citation is a <map:citation-ref>.
 *
 * For example:
 *  @code
 *    <map:citation-ref sass-href="/btr/1/1-2/29.atom" base="http://sass-dev.highwire.org" legacy-resource-id="btr;1/1-2/29"/>
 *  @endcode
 */
class Citation extends TocElementBase {

  /**
   * @return string
   *   Returns the apath of a citation.
   */
  public function apath() {
    return $this->getAttribute('sass-href');
  }

}
