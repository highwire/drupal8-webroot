<?php

namespace HighWire\Parser;

use BetterDOMDocument\DOMDoc;

/**
 * Abstract base class wraps a DOMElement.
 */
abstract class DOMElementBase {

  /**
   * Wrapped DOMElement.
   *
   * @var \DOMElement|false
   */
  public $elem;

  /**
   * Container document.
   *
   * @var \BetterDOMDocument\DOMDoc
   */
  protected $dom;

  /**
   * Default XML to load if none is provided.
   *
   * @var string
   */
  protected $default_xml;

  /**
   * Additional namespaces to register. Array in ['prefix' => 'uri'] format.
   *
   * @var array
   */
  protected $namespaces = [];

  /**
   * Construct a new DOMElementBase.
   *
   * @param \DOMElement|string $xml
   *   The XML or DOMElement with which to build the DOMElementBase.
   * @param \BetterDOMDocument\DOMDoc $dom
   *   The parent DOMDoc of the provided element.
   */
  public function __construct($xml = '', DOMDoc $dom = NULL) {
    if (empty($xml) && !empty($this->default_xml)) {
      $xml = $this->default_xml;
    }

    if (!empty($xml)) {
      if (is_a($xml, "DOMElement")) {
        $this->elem = $xml;
      }
      else {
        $doc = new DOMDoc($xml);
        foreach ($this->namespaces as $prefix => $uri) {
          $doc->registerNamespace($prefix, $uri);
        }
        $this->elem = $doc->documentElement;
        if (empty($dom)) {
          $dom = $doc;
        }
      }
    }
    if (!empty($dom)) {
      $this->setDom($dom);
    }
  }

  /**
   * Set the parent DOM for this element.
   *
   * @param \BetterDOMDocument\DOMDoc $dom
   *   The DOM to which this element belongs.
   */
  public function setDom(DOMDoc $dom) {
    $this->dom = $dom;
  }

  /**
   * Get a string representation of this element.
   *
   * @return string
   *   XML as a string
   */
  public function out() {
    return $this->dom->out($this->elem);
  }

  /**
   * Implements the PHP stringer interface.
   *
   * @return string
   *   XML as a string
   *
   * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
   */
  public function __toString() {
    return $this->out();
  }

  /**
   * Given an xpath, get a single node (first one found)
   *
   * @param string $xpath
   *   XPath to be used for query.
   *
   * @return mixed
   *   The first node found by the xpath query
   *
   * @see \BetterDOMDOcument\DOMDoc::xpathSingle
   */
  public function xpathSingle($xpath) {
    return $this->dom->xpathSingle($xpath, $this->elem);
  }

  /**
   * Given an xpath, get a list of nodes.
   *
   * @param string $xpath
   *   XPath to be used for query.
   *
   * @return \BetterDOMDocument\DOMList|false
   *   An iterable DOMList
   *
   * @see \BetterDOMDOcument\DOMDoc::xpath
   */
  public function xpath($xpath) {
    return $this->dom->xpath($xpath, $this->elem);
  }

  /**
   * Append a child to the ACElement, make it the last child.
   *
   * @param mixed $element
   *   $element can either be an XML string, a DOMDocument, a DOMElement.
   *
   * @return \DOMElement|false
   *   The $newnode, properly attached to DOMDocument.
   *   If you passed $newnode as a DOMElement
   *   then you should replace your DOMElement with the returned one.
   *
   * @see \BetterDOMDOcument\DOMDoc::append
   */
  public function append($element) {
    return $this->dom->append($element, $this->elem);
  }

  /**
   * Get the named attribute value from the element.
   *
   * @param string $name
   *   Name of the attribute.
   *
   * @return string
   *   Attribute value
   *
   * @see DOMElement::getAttribute
   */
  public function getAttribute($name) {
    return $this->elem->getAttribute($name);
  }

  /**
   * Set the named attribute with the given value.
   *
   * @param string $name
   *   Name of the attribute.
   * @param mixed $value
   *   Value for the attribute.
   *
   * @see DOMElement::setAttribute
   */
  public function setAttribute($name, $value) {
    return $this->elem->setAttribute($name, $value);
  }

  /**
   * Set the nodeValue of the element.
   *
   * @param string $value
   *   The value to set.
   *
   * @return \HighWire\Parser\DOMElementBase
   *   Return self for method chaning.
   */
  public function setNodeValue($value) {
    $this->elem->nodeValue = $value;
    return $this;
  }

  /**
   * Get all attributes on the node.
   *
   * @return array
   *   An array of attributes keyed by attr name.
   */
  public function getAttributes(): array {
    $attributes = [];
    if (!empty($this->elem) && $this->elem->hasAttributes()) {
      foreach ($this->elem->attributes as $attr) {
        $attributes[$attr->nodeName] = $attr->nodeValue;
      }
    }
    return $attributes;
  }

}
