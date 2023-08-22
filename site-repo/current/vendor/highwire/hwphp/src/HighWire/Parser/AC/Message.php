<?php

namespace HighWire\Parser\AC;

/**
 * Access Control Message.
 *
 * @see Response
 *
 * Example XML:
 * @code
 * <ac:message name="logged-in" module="username-password" />
 * @endcode
 */
class Message extends ACElement {

  /**
   * Get the machine-name of this message.
   *
   * @return string
   *   Example: "logged-in"
   */
  public function getName() {
    return $this->getAttribute('name');
  }

  /**
   * Get the AC module (not drupal module) machine-name of this message.
   *
   * @return string
   *   Example: "username-password"
   */
  public function getModule() {
    return $this->getAttribute('module');
  }

  /**
   * Get the text of the message that should be displayed to the user.
   *
   * @return string
   *   Example: "You have successfully logged in!"
   */
  public function getText() {
    return trim($this->elem->nodeValue);
  }

  /**
   * Check if this message is an error.
   *
   * @return bool
   *   TRUE if this message is an error, FALSE if it is not an error.
   */
  public function isError() {
    return $this->elem->tagName == 'ac:error';
  }

}
