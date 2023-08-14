<?php

use HighWire\Utility\Str;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase {

  public function testContains() {
    $this->assertTrue(Str::contains("Start", "BlahStart"));
    $this->assertFalse(Str::contains("tikiboo", "unicorns"));
  }

  public function testStartsWith() {
    $this->assertTrue(Str::startsWith("Bla", "BlahBlahBlah"));
    $this->assertTrue(Str::startsWith(["Bla", "foo"], "BlahBlahBlah"));
    $this->assertFalse(Str::startsWith("Goblins", "Wizard of Oz"));
    $this->assertFalse(Str::startsWith(["Goblins", "asdf"], "Wizard of Oz"));
  }

  public function testEndsWith() {
    $this->assertTrue(Str::endsWith("zinng", "Bazinng"));
    $this->assertTrue(Str::endsWith("", "Bazinng"));
    $this->assertTrue(Str::endsWith(["asdf", ""], "Bazinng"));
    $this->assertFalse(Str::endsWith("Pikachu", "Charizard"));
    $this->assertFalse(Str::endsWith(["Pika", "Chu"], "Charizard"));
  }

  public function testWordTrim() {
    $this->assertEquals(Str::wordTrim("This is the best", 11), "This is the");
    $this->assertEquals(Str::wordTrim("This is the best", 15, "..."), "This is the...");
  }

  public function testSanitizeMachineName() {
    $this->assertEquals(Str::sanitizeMachineName("Yo-da"), "Yo_da");
  }

  public function testUnsanitizeMachineName() {
    $this->assertEquals("Yo-da", Str::unsanitizeMachineName("Yo_da"));
  }
}
