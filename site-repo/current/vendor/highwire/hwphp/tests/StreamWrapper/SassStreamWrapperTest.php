<?php

include_once(__DIR__ . '/SassStreamWrapperMock.php');
include_once(__DIR__ . '/SassStreamWrapperClientMock.php');


use HighWire\test\StreamWrapper\SassStreamWrapperMock;
use HighWire\test\StreamWrapper\SassStreamWrapperClientMock;
use HighWire\Clients\Sass\Sass;
use PHPUnit\Framework\TestCase;

/**
 * Sass Stream Wrapper test
 */
class SassStreamWrapperTest extends TestCase {

  public static function setUpBeforeClass() {
    SassStreamWrapperMock::registerStreamWrapper();
  }

  public function testStreamWrapper() {
    // Test file_get_contents
    $content = file_get_contents('sass://corpus/1/2/3.atom');
    $this->assertEquals(trim($content), 'test');

    // Test stat
    $stat = stat('sass://corpus/1/2/3.atom');
    $this->assertEquals(12345, $stat['size']);
    $this->assertEquals(1445412480, $stat['mtime']);

    // Test open and seek
    $handler = fopen('sass://corpus/1/2/3.atom', 'r');
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
    $wrapper = new SassStreamWrapperMock();
    $wrapper->setUri('sass://corpus/1/2/3.atom');
    $this->assertEquals($wrapper->getExternalUrl(), 'http://sass.highwire.org/corpus/1/2/3.atom');
    $this->assertEquals('sass://corpus/1/2/3.atom', $wrapper->getUri());
    $this->assertEquals(SassStreamWrapperMock::READ, $wrapper->getType());
    $this->assertEquals('sass://corpus/1/2/3.atom', $wrapper->realpath());
    $this->assertFalse($wrapper->dirname());
    $this->assertFalse($wrapper->dirname('sass://corpus/1/2/3.atom'));
    $this->assertTrue($wrapper->stream_lock('some_operation'));
    $this->assertTrue($wrapper->stream_set_option(STREAM_OPTION_READ_TIMEOUT, 'somearg', 'somearg'));
    $this->assertFalse($wrapper->stream_set_option('someoption', 'somearg', 'somearg'));


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

    $this->assertFalse(@mkdir('binary://corpus'));

    // Test that rename fails
    try {
      rename('binary://corpus', 'binary://corpus2'); // fail here
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    $this->assertFalse(@rename('binary://corpus', 'binary://corpus2'));

    // Test that rmdir fails
    try {
      rmdir('binary://corpus'); // fail here
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    $this->assertFalse(@rmdir('binary://corpus'));

    // Test unreacable methods
    $wrapper = new SassStreamWrapperMock();

    // Test steam_write
    try {
      $wrapper->stream_write('data');
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    $this->assertEquals(0, @$wrapper->stream_write('data'));

    $this->assertFalse($wrapper->stream_flush());

    // Test stream_metadata
    try {
      $wrapper->stream_metadata('uri', 'option', 'value');
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    $this->assertFalse(@$wrapper->stream_metadata('uri', 'option', 'value'));


    // Test stream_truncate
    try {
      $wrapper->stream_truncate(1000);
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    $this->assertFalse(@$wrapper->stream_truncate(1000));

    // Test unlink
    try {
      $wrapper->unlink('uri');
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    $this->assertTrue(@$wrapper->unlink('uri'));

    // closedir
    try {
      $this->assertEmpty($wrapper->dir_closedir());
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    $this->assertFalse(@$wrapper->dir_closedir());
    $this->assertFalse(@$wrapper->dir_opendir('mydir', 0));

    $this->assertFalse($wrapper->stream_cast('somearg'));

    //dirread
    try {
      $this->assertEmpty($wrapper->dir_readdir());
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    $this->assertFalse(@$wrapper->dir_readdir());



    //rewinddir
    try {
      $this->assertEmpty($wrapper->dir_rewinddir());
      $this->assertTrue(FALSE);
    }
    catch (Exception $e) {
      $this->assertTrue(TRUE);
    }

    $this->assertEquals('HighWire Sass stream wrapper', $wrapper->getName());
    $this->assertEquals('HighWire Sass stream wrapper', $wrapper->getDescription());
  }

  public function testClient() {
    $wrapper = new SassStreamWrapperClientMock();
    $client = $wrapper->getClient();
    $this->assertEquals(Sass::class, get_class($client));
    $client2 = $wrapper->getClient();
    $this->assertEquals($client2, $client);
  }

}
