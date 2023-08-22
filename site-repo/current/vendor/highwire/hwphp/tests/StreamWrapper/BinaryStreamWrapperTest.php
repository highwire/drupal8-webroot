<?php

include_once(__DIR__ . '/BinaryStreamWrapperMock.php');
include_once(__DIR__ . '/BinaryStreamWrapperClientMock.php');

use HighWire\test\StreamWrapper\BinaryStreamWrapperMock;
use HighWire\test\StreamWrapper\BinaryStreamWrapperClientMock;
use HighWire\Clients\Binary\Binary;
use PHPUnit\Framework\TestCase;

/**
 * Binary Stream Wrapper test
 */
class BinaryStreamWrapperTest extends TestCase {

  public static function setUpBeforeClass() {
    BinaryStreamWrapperMock::registerStreamWrapper();
  }

  public function testStreamWrapper() {

    // Test file_get_contents
    $content = file_get_contents('binary://corpus/3321ec26b4e582c0/f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2');
    $this->assertEquals(trim($content), 'test');

    // Test stat
    $stat = stat('binary://corpus/3321ec26b4e582c0/f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2');
    $this->assertEquals(12345, $stat['size']);
    $this->assertEquals(1445412480, $stat['mtime']);

    // Test open and seek
    $handler = fopen('binary://corpus/3321ec26b4e582c0/f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2', 'r');
    fseek($handler, 4);
    $this->assertEquals('seek', fread($handler, 4));

    // Read past the end of the stream
    $this->assertEmpty(fread($handler, 1));
    $this->assertTrue(feof($handler));

    // Reset the stream
    rewind($handler);
    $this->assertEquals('test', fread($handler, 4));
    $this->assertFalse(feof($handler));

    // Close the stream
    $this->assertTrue(fclose($handler));

    // Test public URL
    // TODO: Update this when production binary service URL comes online.
    $wrapper = new BinaryStreamWrapperMock();
    $wrapper->setUri('binary://corpus/3321ec26b4e582c0/f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2/myfile.pdf');
    $this->assertEquals($wrapper->getExternalUrl(), 'http://bin-svc.highwire.org/binary/3321ec26b4e582c0/f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2/myfile.pdf');
  }

  public function testUnimplementedMethods() {

    // Test that opendir fails.
    try {
      opendir('binary://corpus'); // fail here
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    // Test that mkdir fails.
    try {
      mkdir('binary://corpus'); // fail here
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    // Test that rename fails
    try {
      rename('binary://corpus', 'binary://corpus2'); // fail here
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    // Test that rmdir fails
    try {
      rmdir('binary://corpus'); // fail here
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    // Test unreacable methods
    $wrapper = new BinaryStreamWrapperMock();

    // closedir
    try {
      $this->assertEmpty($wrapper->dir_closedir());
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    //rewinddir
    try {
      $this->assertEmpty($wrapper->dir_rewinddir());
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    $this->assertEquals('HighWire Binary stream wrapper', $wrapper->getName());
    $this->assertEquals('HighWire Binary stream wrapper', $wrapper->getDescription());

  }

  public function testClient() {
    $wrapper = new BinaryStreamWrapperClientMock();
    $client = $wrapper->getClient();
    $this->assertEquals(Binary::class, get_class($client));
    $client2 = $wrapper->getClient();
    $this->assertEquals($client2, $client);
  }

}
