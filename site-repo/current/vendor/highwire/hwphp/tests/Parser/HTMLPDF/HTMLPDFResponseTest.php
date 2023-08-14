<?php

use PHPUnit\Framework\TestCase;
use HighWire\Parser\HTMLPDF\ResponseItems;

/**
 * Class HTMLPDFResponseTest
 */
class HTMLPDFResponseTest extends TestCase {

  public function testParsingResponse() {
    $xml = file_get_contents(__DIR__  . '/response.xml');
    $response = new ResponseItems($xml);
    $items = $response->getItems();
    $this->assertEquals(count($items), 2);
    $this->assertEquals('/mheaeworks/book/9780070071391/chapter/chapter1.atom', $items['/mheaeworks/book/9780070071391/chapter/chapter1.atom']->getApath());
    $this->assertEquals('http://bin-svc-dev.highwire.org/entity/1252e21d1507e30a/e6f49bb2db9839a4d753d7cf4f43fef62593ae08a99661719dfeaf13fb302349', $items['/mheaeworks/book/9780070071391/chapter/chapter1.atom']->getURL());
    $this->assertEquals('1252e21d1507e30a', $items['/mheaeworks/book/9780070071391/chapter/chapter1.atom']->getIngestKey());
    $this->assertEquals('e6f49bb2db9839a4d753d7cf4f43fef62593ae08a99661719dfeaf13fb302349', $items['/mheaeworks/book/9780070071391/chapter/chapter1.atom']->getBinaryHash());

  }
}
