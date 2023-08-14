<?php

namespace HighWire\Parser\AC;

/**
 * Access Control Authorizing Entitlements.
 *
 * Example XML:
 * @code
 * <ac:authorizing-entitlements type="application/vnd.hw.sigma+json">
 * [insert-json-object]
 * </ac:authorizing-entitlements>
 * @endcode
 */
class AuthorizingEntitlements extends ACElement {


  /**
   * {@inheritdoc}
   */
  protected $default_xml = '<ac:authorizing-entitlements xmlns:ac="http://schema.highwire.org/Access" />';

  /**
   * Set the type attribute
   *
   * @param string $type
   *   Type is a string that represent an http
   *   content-type.
   *
   * @return self
   *   Return self for chaining.
   */
  public function setType($type) {
    $this->setAttribute('type', $type);
    return $this;
  }


  /**
   * Get the type.
   *
   * @return string
   *   Get the type attribute that represents the content-type
   *   of the data we are sending.
   */
  public function getType() {
    return $this->getAttribute('type');
  }

  /**
   * Set entitlement data.
   *
   * @param string $data
   *   The data to set as the dom node value.
   *
   * @return self
   *   Return self for chaining.
   */
  public function setData($data) {
    $this->setNodeValue($data);
    return $this;
  }

}
