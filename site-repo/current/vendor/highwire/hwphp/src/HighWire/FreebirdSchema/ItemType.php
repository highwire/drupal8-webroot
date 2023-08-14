<?php

namespace HighWire\FreebirdSchema;

use HighWire\Utility\Str;

/**
 * Item Type container.
 */
class ItemType {
  private $name;
  private $fields = [];

  /**
   * Create a schema object.
   *
   * @param string $name
   *   The name of this content types.
   */
  public function __construct($name) {
    $this->setName($name);
  }

  /**
   * Get all fields keyed by field name.
   *
   * @return array
   *   Associative array of HighWire\FreebirdSchema\Field objects, keyed by Field name.
   */
  public function getFields() {
    return $this->fields;
  }

  /**
   * Get a single Field.
   *
   * @param string $name
   *   The name of the property to fetch.
   *
   * @return \HighWire\FreebirdSchema\Field
   *   A Freebird field object.
   */
  public function getFieldByName($name) {
    return $this->fields[$name] ?? NULL;
  }

  /**
   * Get the name of the type.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Set the name of this item type.
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * Get the sanitized name of this item type.
   * This value should be safe for things like machine names in
   * drupal.
   *
   * @return string
   *   The sanitized name of the item type.
   */
  public function getSanitizedName() {
    return Str::sanitizeMachineName($this->name);
  }

  /**
   * Not for external use.
   */
  public function addField(Field $field) {
    $this->fields[$field->getName()] = $field;
  }

}
