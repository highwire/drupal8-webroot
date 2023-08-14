<?php

namespace HighWire\FreebirdSchema;

use HighWire\Utility\Str;

/**
 * A field is an object that represents a 'freebird' field.
 *
 * Right now this created based on a combination of
 * the extract policy and atomx(elastic) index fields.
 */
class Field {
  private $name;
  private $type;
  private $drupalType;
  private $label;
  private $description;
  private $isMultiple = FALSE;
  private $childFields = [];
  private $attributes = [];

  /**
   * Create a field obejct.
   *
   * @param string $type
   *   The field type.
   * @param string $name
   *   The name of this field.
   */
  public function __construct($type, $name) {
    $this->setName($name);
    $this->setType($type);
  }

  /**
   * Set attributes for this field.
   *
   * @param array $attributes
   *   An array of field attributes.
   */
  public function setAttributes(array $attributes) {
    $this->attributes = $attributes;
  }

  /**
   * Get the field attributes.
   *
   * @return array
   *   An array of field attributes.
   */
  public function getAttributes() {
    return $this->attributes;
  }

  /**
   * Get the name of the field.
   *
   * @return string
   *   The name of the field
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Set the name of the field represented in the extract policy object.
   *
   * @param string $name
   *   The name of the field.
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * Get the sanitized name of this field.
   *
   * This value should be safe for things like machine names in
   * drupal.
   *
   * @return string
   *   The sanitized name of the field.
   */
  public function getSanitizedName() {
    return Str::sanitizeMachineName($this->name);
  }

  /**
   * The type as specified in the extract policy "elastic-type" attribute, or autodetected by elastic.
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Set the field set.
   *
   * @param string $type
   *   Set the field type.
   */
  public function setType(string $type) {
    $this->type = $type;
  }

  /**
   * The drupal field type as specified in the extract policy "drupal-type" attribute.
   */
  public function getDrupalType() {
    return $this->drupalType;
  }

  /**
   * Set the drupal field field type.
   *
   * @param string $type
   *   Set the field type.
   */
  public function setDrupalType(string $type) {
    $this->drupalType = $type;
  }

  /**
   * The human readable label for the field.
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * Set the field label.
   *
   * @param string $label
   *   The label.
   */
  public function setLabel($label) {
    $this->label = $label;
  }

  /**
   * The human readable description for the field.
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Set the description.
   *
   * @param string $description
   *   The description as a string.
   */
  public function setDescription($description) {
    $this->description = $description;
  }

  /**
   * Does this field contain a list of values, or a single value?
   *
   * @return bool
   *   Whether the field is a list
   */
  public function isMultiple() {
    return $this->isMultiple;
  }

  /**
   * If this field can have multiple values pass TURE, otherwise FALSE.
   *
   * @param bool $is_multiple
   *   Returns true if the field can store more than one value.
   */
  public function setIsMultiple($is_multiple) {
    $this->isMultiple = $is_multiple;
  }

  /**
   * For fields where policyType() is 'structure', return all subfields keyed by field name.
   *
   * @return array
   *   An associative array of Field objects, keyed by the Field name.
   */
  public function getChildFields() {
    return $this->childFields;
  }

  /**
   * Add a child field.
   *
   * @param Field $child_field
   *   A child field of this field, will be keyed by field type.
   */
  public function addChildField(Field $child_field) {
    $this->childFields[$child_field->getName()] = $child_field;
  }

  /**
   * Get a child field by name.
   */
  public function getChildFieldByName($name) {
    return $this->childFields[$name] ?? NULL;
  }

  /**
   * Check if the elastic field has structure (Is a complex field).
   */
  public function hasChildFields() {
    return !empty($this->childFields) ? TRUE : FALSE;
  }

}
