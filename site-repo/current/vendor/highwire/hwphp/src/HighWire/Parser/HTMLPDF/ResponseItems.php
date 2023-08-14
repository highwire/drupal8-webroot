<?php

namespace HighWire\Parser\HTMLPDF;

use BetterDOMDocument\DOMDoc;

/**
 * ResponseItem holds a single "<ResponseItem>" element.
 *
 * For example:
 *  @code
 *  <ResponseItems>
 *    <Items>
 *      <Apath>/mheaeworks/book/9780070071391/chapter/chapter1.atom</Apath>
 *      <URL>http://bin-svc-dev.highwire.org/entity/1252e21d1507e30a/e6f49bb2db9839a4d753d7cf4f43fef62593ae08a99661719dfeaf13fb302349</URL>
 *      <IngestKey>1252e21d1507e30a</IngestKey>
 *      <Hash>e6f49bb2db9839a4d753d7cf4f43fef62593ae08a99661719dfeaf13fb302349</Hash>
 *    </Items>
 *    <Items>
 *      <Apath>/mheaeworks/book/9780070071391/chapter/chapter2.atom</Apath>
 *      <URL>http://bin-svc-dev.highwire.org/entity/f30051e412d94861/187fdef2ef26151fcc9e3c5efc458f7a2d4eea9aeb1dd42d41dc420e51bbbc4f</URL>
 *      <IngestKey>f30051e412d94861</IngestKey>
 *      <Hash>187fdef2ef26151fcc9e3c5efc458f7a2d4eea9aeb1dd42d41dc420e51bbbc4f</Hash>
 *    </Items>
 *  </ResponseItems>
 *  @endcode
 */
class ResponseItems extends DOMDoc {

  /**
   * Get the response items.
   *
   * @return ResponseItem[]
   *   An array of response items.
   */
  public function getItems() {
    $items = [];
    $elems = $this->xpath("//Items");

    foreach ($elems as $elem) {
      $item = new ResponseItem($elem, $this);
      $items[$item->getApath()] = $item;
    }

    return $items;
  }

}
