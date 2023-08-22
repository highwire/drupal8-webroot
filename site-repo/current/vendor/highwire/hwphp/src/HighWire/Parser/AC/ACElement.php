<?php

namespace HighWire\Parser\AC;

use HighWire\Parser\DOMElementBase;
use Ramsey\Uuid\Uuid;
use BetterDOMDocument\DOMDoc;

/**
 * Abstract base class for all AC Elements.
 */
abstract class ACElement extends DOMElementBase {

  /**
   * Attribute on the element to use as the unqiue identifier.
   *
   * @var string
   */
  protected $id_attribute;

  /**
   * {@inheritdoc}
   */
  protected $namespaces = [
    'ac' => 'http://schema.highwire.org/Access',
    'gen' => 'http://schema.highwire.org/Site/Generator',
  ];

  /**
   * Construct a new ACElement.
   *
   * @param string|\DOMElement $xml
   *   The XML or DOMElement with which to build the ACElement.
   *
   * @param \BetterDOMDocument\DOMDoc $dom
   *   The parent DOMDoc of the provided element.
   */
  public function __construct($xml = '', DOMDoc $dom = NULL) {
    parent::__construct($xml, $dom);

    if (!empty($this->id_attribute)) {
      $this->setId();
    }
  }

  /**
   * Set the ID for the element. The 'id_attribute' property must be set
   * or this will throw an exception.
   *
   * @param null|string $id
   *   The unique ID for the element. If not provided, an ID will be generated.
   *
   * @return self
   *   Return self for method chaining.
   */
  public function setId($id = NULL) {
    if (empty($this->id_attribute)) {
      throw new \Exception("Cannot set id on ACElement where no id attribute has been specified");
    }

    if (empty($id)) {
      if (!empty($this->getId())) {
        return $this;
      }
      $uuid = Uuid::uuid1();
      $id = $uuid->toString();
    }

    $this->setAttribute($this->id_attribute, $id);
    return $this;
  }

  /**
   * Get the ID stored in the ID attribute (specified by the id_attribute)
   *
   * @return string
   *   The unique ID for the element. Note that this ID is not stable across
   *   multiple subsequent requests / responses.
   */
  public function getId() {
    if (empty($this->id_attribute)) {
      throw new \Exception("Cannot set id on ACElement where no id attribute has been specified");
    }
    return $this->getAttribute($this->id_attribute);
  }

}
