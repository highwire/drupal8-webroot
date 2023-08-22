<?php

namespace HighWire\Parser\HTMLPDF;

use HighWire\Parser\DOMElementBase;

/**
 * ResponseItem holds a single "<ResponseItem>" element.
 *
 * For example:
 *  @code
 *  <ResponseItem>
 *    <Apath>/mheaeworks/book/9780070071391/chapter/chapter1.atom</Apath>
 *    <URL>http://bin-svc-dev.highwire.org/entity/1252e21d1507e30a/e6f49bb2db9839a4d753d7cf4f43fef62593ae08a99661719dfeaf13fb302349</URL>
 *    <IngestKey>1252e21d1507e30a</IngestKey>
 *    <Hash>e6f49bb2db9839a4d753d7cf4f43fef62593ae08a99661719dfeaf13fb302349</Hash>
 *  </ResponseItem>
 *  @endcode
 */
class ResponseItem extends DOMElementBase {

  /**
   * Get the apath.
   *
   * @return string|null
   *   The apath or null.
   */
  public function getApath() {
    return $this->xpathSingle('./Apath')->nodeValue;
  }

  /**
   * Get the binary hash.
   *
   * @return string|null
   *   Returns the binary hash or null if not found.
   */
  public function getBinaryHash() {
    return $this->xpathSingle('./Hash')->nodeValue;
  }

  /**
   * Get the ingest key.
   *
   * @return string|null
   *   The ingest key or null.
   */
  public function getIngestKey() {
    return $this->xpathSingle('./IngestKey')->nodeValue;
  }

  /**
   * Get the URL.
   *
   * @return string|null
   *   The url or null.
   */
  public function getURL() {
    return $this->xpathSingle('./URL')->nodeValue;
  }

}
