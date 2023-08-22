<?php

namespace HighWire\Parser\AC;

/**
 * Access Control Credential.
 *
 * @see Response
 *
 * Example XML:
 * @code
 * <ac:credentials method="username">tolga1</ac:credentials>
 * @endcode
 */
class Credential extends ACElement {

  /**
   * Get the method that provided this credential.
   *
   * @return string
   *   Example: "username"
   */
  public function getMethod() {
    return $this->getAttribute('method');
  }

  /**
   * Get the value of the method that provided this credential.
   *
   * @return string
   *   Example: "john.doe"
   */
  public function getValue() {
    return trim($this->elem->nodeValue);
  }

}
