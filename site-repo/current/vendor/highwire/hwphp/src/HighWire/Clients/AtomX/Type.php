<?php

namespace HighWire\Clients\AtomX;

/**
 * Type represents a single type (aka content-type) in the elasticsearch index.
 *
 * It contains a list of fields found for this type,
 * using data from both AtomX (elasticsearch).
 */
class Type {
  protected $name;
  protected $properties = [];

  /**
   * Create an elastic type object.
   */
  public function __construct($name, $properties = []) {
    $this->name = $name;
    $this->properties = $properties;
  }

  /**
   * Get all properties, keyed by property name.
   *
   * @return HighWire\Clients\AtomX\Property[]
   *   Associative array of Property objects, keyed by Property name.
   */
  public function getProperties() {
    return $this->properties;
  }

  /**
   * Get a single Property.
   *
   * @param string $name
   *   The name of the property to fetch.
   */
  public function getPropertyByName($name) {
    return $this->properties[$name] ?? NULL;
  }

  /**
   * Get the name of the type. Matches the name of the type in elasticsearch.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Not for external use.
   */
  public function addProperty(Property $property) {
    $this->properties[$property->getName()] = $property;
  }

}
