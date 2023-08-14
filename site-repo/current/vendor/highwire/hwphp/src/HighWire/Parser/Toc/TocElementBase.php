<?php

namespace HighWire\Parser\Toc;

use HighWire\Parser\DOMElementBase;


class TocElementBase extends DOMElementBase {

  /**
   * Additional namespaces to register. Array in ['prefix' => 'uri'] format.
   *
   * @var array
   */
  protected $namespaces = [
    'map' => 'http://schema.highwire.org/Journal/Index/Manifest',
  ];

}
