<?php

use HighWire\Parser\ExtractPolicy\ExtractPolicy;
use PHPUnit\Framework\TestCase;

class ExtractPolicyTest extends TestCase {

  public function testExtractPolicy() {
    $policy = new ExtractPolicy(file_get_contents(__DIR__  . '/../../assets/extract.definition.test.xml'));

    $this->assertEquals($policy->getName(), 'freebird-journal');
    $this->assertEquals($policy->getPrimaryIdField(), 'apath');
    $this->assertEquals($policy->getCorpusField(), 'jcode');
    $this->assertEquals($policy->getTypeField(), 'atype-long');
    $this->assertTrue($policy->hasApplication('drupal'));
    $this->assertTrue($policy->hasApplication('atomx'));
    $this->assertFalse($policy->hasApplication('asdfasdf'));

    $fields = $policy->fields();
    $this->assertNotEmpty($fields);
    $this->assertNotEmpty($fields['book-permissions']);
    $this->assertEquals($fields['book-permissions']->name(), 'book-permissions');
    $this->assertEquals(trim($fields['book-permissions']->xpathValue()), 'nlm:book-meta/nlm:permissions');

    $field = $policy->getField('book-meta');
    $this->assertEquals('book-meta', $field->name());
    $this->assertEquals('book-meta', $field->path());
    $this->assertEquals('structure', $field->type());
    $this->assertEquals('Book Metadata', $field->label());
    $this->assertEquals('Metadata about books', $field->description());
    $this->assertEquals('$book-parent', $field->xpathValue());
    $this->assertEquals("\$atype-long = ('book-section') or \$atype-long = ('book-fragment')", $field->includeIf());
    $this->assertEquals(9, count($field->structure()));

    $variables = $policy->variables();
    $this->assertNotEmpty($variables);
    $this->assertEquals('apath', $variables['apath']->name());
    $this->assertEquals('', $variables['apath']->includeIf());
    $this->assertEquals("(atom:link[@rel='self']/@href)[1]", trim($variables['apath']->xpathValue()));

    $flat = $policy->flatFields();
    $this->assertNotEmpty($flat);
    
    $fields = $policy->xpath('//policy:field');
    $this->assertEquals(count($flat), count($policy->xpath('//policy:field')));

    $policy->verify();

    // Try to get non-existent field
    $this->assertEmpty($policy->getField('asdfadfasdf'));
  }

  /**
   * @expectedException HighWire\Exception\HighWireExtractPolicyInvalid
   */
  public function testExtractPolicyInvalid() {
    $policy = new ExtractPolicy('<policy xmlns="http://schema.highwire.org/DataExtract"
        xmlns:atom="http://www.w3.org/2005/Atom"
        xmlns:nlm="http://schema.highwire.org/NLM/Journal"
        xmlns:hwp="http://schema.highwire.org/Journal"
        xmlns:c="http://schema.highwire.org/Compound"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:collection="http://schema.highwire.org/Collection"
        xmlns:metadata="http://schema.highwire.org/Service/Metadata"
        xmlns:r="http://schema.highwire.org/Revision"
        xmlns:course="http://schema.highwire.org/Course"
        xmlns:xlink="http://www.w3.org/1999/xlink"></policy>');

    $policy->verify();
  }
}
