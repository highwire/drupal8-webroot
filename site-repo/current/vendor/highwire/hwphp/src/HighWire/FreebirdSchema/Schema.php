<?php

namespace HighWire\FreebirdSchema;

/**
 * The 'freebird' Schema represents the combinations of the extract
 * policy fields and properties from atomx (elastic).
 */
class Schema {

  /**
   * An array of item types that are part of this schema.
   *
   * @var \HighWire\FreebirdSchema\ItemType[]
   */
  private $itemTypes = [];

  /**
   * The id of the schema.
   * Usually the extract policy mame.
   *
   * @var string
   */
  private $id;

  /**
   * Create a freebird schema object.
   *
   * @param string $id
   *   The id of the schema. Usually
   *   the name of the extract policy.
   */
  public function __construct($id) {
    $this->setId($id);
  }

  /**
   * Set the schema id.
   *
   * @param string $id
   *   The id of the schema.
   */
  public function setId($id) {
    $this->id = $id;
  }

  /**
   * Return the schema id.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Add an ItemType for this schema.
   *
   * @param ItemType $item_type
   *   An ItemType object.
   *
   * @throws Exception
   */
  public function addItemType(ItemType $item_type) {
    if (array_key_exists($item_type->getName(), $this->itemTypes)) {
      throw new \Exception("Item type " . $item_type->getName() . " already exists in schema " . $this->getId());
    }

    $this->itemTypes[$item_type->getName()] = $item_type;
  }

  /**
   * Get content types.
   */
  public function getItemTypes() {
    return $this->itemTypes;
  }

  /**
   * Get item types keyed by the sanitized machine name.
   *
   * @return array
   *   An array of items types keyed by the sanitized
   *   machine name.
   */
  public function getSanitizedItemTypes() {
    $sanitized_item_types = [];

    if (!empty($this->itemTypes)) {
      foreach ($this->itemTypes as $item_type) {
        if ($name = $item_type->getSanitizedName()) {
          $sanitized_item_types[$name] = $item_type;
        }
      }
    }

    return $sanitized_item_types;
  }

  /**
   * Get ItemType by name.
   *
   * @param string $name
   *   The item type name.
   *
   * @return null|ItemType
   *   Returns the ItemType otherwise null.
   */
  public function getItemByName($name) {
    if (empty($this->itemTypes)) {
      return NULL;
    }

    return $this->itemTypes[$name] ?? NULL;
  }

  /**
   * Get item by sanitized name.
   *
   * @param string $name
   *   The sanitized name of the ItemType.
   *
   * @return bool|ItemType
   *   Returns the ItemType otherwise FALSE.
   */
  public function getItemSanitizedByName($name) {
    $item_type_return = NULL;

    if (!empty($this->itemTypes)) {
      foreach ($this->itemTypes as $item_type) {
        if ($item_type->getSanitizedName() == $name) {
          $item_type_return = $item_type;
          break;
        }
      }
    }

    return $item_type_return;
  }

  /**
   * Get all fields
   *
   * @return array
   *   Returns all fields, keyed by sanitized name
   */
  public function getAllFields() {
    $all_fields = [];

    foreach ($this->getItemTypes() as $type) {
      foreach ($type->getFields() as $field) {
        $name = $field->getSanitizedName();
        if (empty($all_fields[$name])) {
          $all_fields[$name] = $field;
        }
      }
    }

    return $all_fields;
  }

}
