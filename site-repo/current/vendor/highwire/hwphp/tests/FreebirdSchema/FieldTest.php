<?php

use HighWire\FreebirdSchema\Field;
use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase {

  public function testField() {
    $name = 'date-epreprint';
    $type = 'date';
    $label = 'Electronic Publication Date';
    $drupal_type = 'daterange';
    $description = 'The electronic publication date for a journal article';
    $field = new Field($type, $name);
    $field->setLabel($label);
    $field->setDrupalType($drupal_type);
    $field->setIsMultiple(TRUE);
    $field->setDescription($description);
    $field->setAttributes(['some-attribute' => 'attribute_value']);
    $this->assertEquals(count($field->getAttributes()), 1);

    $childField1 = new Field('nested', 'custom-meta');
    $this->assertFalse($childField1->hasChildFields());
    $childchild1 = new Field('nested', 'custom-meta-test');
    $childField1->addChildField($childchild1);
    $this->assertTrue($childField1->hasChildFields());
    $childField2 = new Field('date', 'date-epub');
    $field->addChildField($childField1);
    $field->addChildField($childField2);

    $this->assertEquals($description, $field->getDescription());
    $this->assertEquals($label, $field->getLabel());
    $this->assertEquals($drupal_type, $field->getDrupalType());
    $this->assertEquals($type, $field->getType());
    $this->assertEquals($name, $field->getName());
    $this->assertEquals('date_epreprint', $field->getSanitizedName());
    $this->assertTrue($field->isMultiple());
    $childFields = $field->getChildFields();
    $this->assertInternalType('array', $childFields);

    foreach ($childFields as $key => $child) {
      $this->assertEquals($child->getName(), $key);
      $this->assertEquals(get_class($child), 'HighWire\FreebirdSchema\Field');
    }

    $childField = $field->getChildFieldByName('custom-meta');
    $this->assertEquals(get_class($childField), 'HighWire\FreebirdSchema\Field');
    $this->assertEquals('custom-meta', $childField->getName());
    $null_child = $field->getChildFieldByName('tickety-boo');
    $this->assertNull($null_child);
  }

}
