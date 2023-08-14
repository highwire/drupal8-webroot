<?php

namespace HighWire\Parser\AC;

/**
 * Access Control Scoped.
 *
 * @see Response
 *
 * Example XML:
 * @code
 * <ac:authorization scope="online" target="resource" uri="/freebird2/book/978-1-6170-5282-8.atom">
 *   <ac:authorized identity="31076b6c" privilege="0b721558"/>
 *   <ac:scoped lang="en" role="abstract" target="variant" type="application/xhtml+xml"/>
 *   <ac:scoped lang="en" role="full-text" target="variant" type="application/xhtml+xml"/>
 *   <ac:scoped lang="en" role="full-text" target="variant" type="application/pdf"/>
 * </ac:authorization>
 * @endcode
 */
class Scoped extends ACElement {

  /**
   * Get the target-type of the authorization response.
   *
   * @return string
   *   Examples of target values include 'resource' and 'service',
   */
  public function getTarget() {
    return $this->getAttribute('target');
  }

  /**
   * Get the role attribute.
   *
   * @return string
   *   The role. It can be things like 'full-text', 'abstract', 'source' etc.
   */
  public function getRole() {
    return $this->getAttribute('role');
  }

  /**
   * Get type attribute
   *
   * @return string
   *   The type. Type relates to the http content type of
   *   the authorized resource.
   */
  public function getType() {
    return $this->getAttribute('type');
  }

  /**
   * Get the language attribute.
   *
   * @return string
   *   The language. The language was scoped to.
   */
  public function getLanguage() {
    return $this->getAttribute('lang');
  }

}
