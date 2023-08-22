<?php

namespace HighWire\Clients\AtomX;

/**
 * A property object.
 */
class Property {
  private $childProperties;
  private $name;
  private $path;
  private $type = NULL;

  /**
   * Create a field property obejct.
   *
   * @param string $name
   *   The elastic field path.
   * @param array $elastic_field
   *   The elastic field schema that is pulled from /_mappings from elastic.
   *
   * @throws \Exception
   */
  public function __construct(string $name, array $elastic_field) {
    $this->setName($name);
    $this->setPath($name);

    if (!empty($elastic_field['type'])) {
      $this->setType($elastic_field['type']);
    }
    else {
      // The field type isn't set, it's a complex field
      // Meaning this property holds an array,
      // object or some sort of nested data type.
      $this->setType('complex');
    }

    if (!empty($elastic_field['properties'])) {
      $this->childProperties = [];
      foreach ($elastic_field['properties'] as $sub_field_name => $sub_field) {
        $child_property = new Property($sub_field_name, $sub_field);
        $child_property->setPath($this->getName() . '.' . $child_property->getName());
        $this->childProperties[$child_property->getName()] = $child_property;
      }
    }
  }

  /**
   * Get the name of the field.
   *
   * For the fields attached directly to the root
   * object, the name and the path are the same.
   *
   * For fields atttached to structures,
   * the name is the last element in the path.
   *
   * @return string
   *   The name of the field.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Set the property name.
   *
   * @param string $name
   *   The name of the property.
   */
  public function setName(string $name) {
    $this->name = $name;
  }

  /**
   * The fully qualified path used to fetch the field.
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * Set the elastic path.
   */
  public function setPath($path) {
    $this->path = $path;
  }

  /**
   * Get the property type.
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Set the type for this property.
   *
   * @param string $type
   *   The type of the property.
   */
  public function setType(string $type) {
    $this->type = $type;
  }

  /**
   * Get child properties.
   */
  public function getChildProperties() {
    return $this->childProperties;
  }

  /**
   * Get child property by name.
   */
  public function getChildPropertyByName($name) {
    return $this->childProperties[$name] ?? NULL;
  }

  /**
   * Check if the propery has child properties.
   *
   * @return bool
   *   Returns TURE|FALSE if the field has children.
   */
  public function hasChildProperties() {
    if (empty($this->childProperties)) {
      return FALSE;
    }

    return TRUE;
  }

}
