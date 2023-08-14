<?php

use HighWire\FreebirdSchema\ItemType;
use HighWire\FreebirdSchema\Field;
use PHPUnit\Framework\TestCase;

class ItemTypeTest extends TestCase {

  public function testField() {
    $name = 'item-book';
    $item = new ItemType($name);
    $child_field1 = new Field('nested', 'custom-meta');
    $child_field2 = new Field('date', 'date-epub');
    $item->addField($child_field1);
    $item->addField($child_field2);

    $this->assertEquals($name, $item->getName());
    $this->assertEquals('item_book', $item->getSanitizedName());
    $child_fields = $item->getFields();
    $this->assertInternalType('array', $child_fields);
    $this->assertEquals(count($child_fields), 2);
    $child_field = $item->getFieldByName('custom-meta');
    $this->assertEquals('custom-meta', $child_field->getName());
    $null_child = $item->getFieldByName('tickety-boo');
    $this->assertNull($null_child);
  }

}
