<?php

use HighWire\FreebirdSchema\ItemType;
use HighWire\FreebirdSchema\Field;
use HighWire\FreebirdSchema\Schema;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase {

  public function testField() {
    $schema_id = 'item-bits';
    $schema = new Schema($schema_id);
    $this->assertNull($schema->getItemByName('no-existent-item'));
    $this->assertEquals($schema_id, $schema->getId());
    $schema->setId('scolaris-journal');
    $this->assertEquals('scolaris-journal', $schema->getId());

    $child_field1 = new Field('nested', 'custom-meta');
    $item1 = new ItemType('journal-article');
    $item1->addField($child_field1);
    $schema->addItemType($item1);
    $item2 = new ItemType('journal-issue');
    $child_field2 = new Field('date', 'date-epub');
    $item2->addField($child_field2);
    $schema->addItemType($item2);

    $items = $schema->getItemTypes();
    $this->assertInternalType('array', $items);
    $this->assertEquals(count($items), 2);

    foreach ($items as $key => $item) {
      $this->assertEquals($key, $item->getName());
    }

    $this->assertNull($schema->getItemByName('tickety-boo'));
    $this->assertEquals($item1, $schema->getItemByName('journal-article'));
    $this->assertNull($schema->getItemSanitizedByName('tickety_boo'));
    $this->assertEquals($item1, $schema->getItemSanitizedByName('journal_article'));

    $sanatized_items = $schema->getSanitizedItemTypes();
    $this->assertInternalType('array', $items);
    $this->assertEquals(count($items), 2);

    foreach ($sanatized_items as $key => $item) {
      $this->assertEquals($key, $item->getSanitizedName());
    }

    $fields = $schema->getAllFields();
    $this->assertInternalType('array', $fields);
    $this->assertEquals(count($fields), 2);
    $this->assertEquals(get_class($fields['date_epub']), 'HighWire\FreebirdSchema\Field');
  }

  /**
   * @expectedException Exception
   */
  public function testAddExistingItemType() {
    $schema_id = 'item-bits';
    $schema = new Schema($schema_id);
    $item1 = new ItemType('journal-article');
    $schema->addItemType($item1);
    $schema->addItemType($item1);
  }

}
