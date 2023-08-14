<?php
 
use HighWire\Utility\Apath;
use PHPUnit\Framework\TestCase;

class ApathTest extends TestCase {

  public function testApath() {
    $apaths = [
      '/bmj/352/bmj.h7010/F1.atom', 
      '/bmj/352/bmj.h7010.atom', 
      '/bmj/352/bmj.i577.atom', 
      '/bmj/352/8048.atom',
      '/bmj/352.atom',
      '/bmj/3.atom', 
      '/bmj.atom',
    ];

    foreach ($apaths as $apath) {
      $jcode = Apath::getCorpus($apath);
      $this->assertEquals($jcode, 'bmj');
      $this->assertTrue(Apath::validate($apath));
    }

    $this->assertEquals(Apath::getCorpusCodes($apaths), ['bmj']);

    $apaths = [
      '/bmj/352/bmj.h7010/F1.atom', 
      '/sci.atom',
    ];

    $this->assertEquals(Apath::getCorpusCodes($apaths), ['bmj', 'sci']);

    $this->assertEquals(Apath::getContentPath('/bmj/352/bmj.h7010/F1.atom'), '/content/352/bmj.h7010/F1');
    $this->assertEquals(Apath::getContentPath('/bmj.atom'), '/content');
    
    $this->assertEquals(Apath::getLongContentPath('/bmj/352/bmj.h7010/F1.atom'), '/content/bmj/352/bmj.h7010/F1');
    $this->assertEquals(Apath::getLongContentPath('/bmj.atom'), '/content/bmj');

    // Test bad apaths
    $bad_apaths = [
      '/bmj/352/bmj.h7010/F1',
      '/bmj//352/bmj.h7010.atom',
      'bmj/352/bmj.i577.atom',
      '/bmj/352/bmj.h7010.atom?query-params=not-allowed.atom',
    ];
    foreach ($bad_apaths as $apath) {
      $this->assertFalse(Apath::validate($apath));
    }

  }

  public function testCorpusCode() {
    $this->assertTrue(Apath::validateCorpusCode('bmj'));
    $this->assertTrue(Apath::validateCorpusCode('bmj360'));
    $this->assertTrue(Apath::validateCorpusCode('ak'));

    $this->assertFalse(Apath::validateCorpusCode('ABC'));
    $this->assertFalse(Apath::validateCorpusCode('ABC360'));
    $this->assertFalse(Apath::validateCorpusCode('123'));
    $this->assertFalse(Apath::validateCorpusCode('a'));
    $this->assertFalse(Apath::validateCorpusCode('1'));
    $this->assertFalse(Apath::validateCorpusCode('bmjABC'));
  }
}
