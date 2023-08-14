<?php

use HighWire\Parser\Markup\Markup;
use HighWire\Parser\Markup\MarkupProcessor;
use HighWire\Parser\ExtractPolicy\ExtractPolicy;
use PHPUnit\Framework\TestCase;

class MarkupTest extends TestCase {

  public function testMarkup() {
    $markup = new Markup(file_get_contents(__DIR__  . '/../assets/extract.definition.test.xml'));
    $markup->addProcessor(new testProcessor());
    $markup->setContext($this);

    $policy = new ExtractPolicy($markup->out());

    $this->assertEquals($policy->getName(), 'freebird-journal');
  }

  public function testLoadMarlkup() {
    $markup = new Markup(file_get_contents(__DIR__  . '/markup/markup1.html'));
    $this->assertNotFalse($markup->xpathSingle('//html:html'));

    $markup = Markup::loadMarkup("file://" . __DIR__  . '/markup/markup1.html');
    $this->assertNotFalse($markup->xpathSingle('//html:html'));
  }

  public function testHTML5Markup() {
    $markup = new Markup(file_get_contents(__DIR__  . '/markup/markup2.html'));

    $markup2 = new Markup($markup->out());

    $this->assertEquals($markup2->out('//button'), '<button class="btn showhide-button collapsed" data-toggle="collapse" data-target="#ch01q1-answer" aria-expanded="false" xmlns:xhtml="http://www.w3.org/1999/xhtml"> answer </button>');
  }
}

class testProcessor implements MarkupProcessor {
  public function id() {
    return 'test';
  }

  public function process(Markup $markup, $context = NULL) {
    // Change the policy name from freebird-journal to 'test'
    $markup->documentElement->setAttribute('name', 'test');

    // The context is MarkupTest, make sure it works. 
    $context->assertTrue(TRUE);
  }
}
