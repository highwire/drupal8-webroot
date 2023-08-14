<?php

namespace HighWire\Parser\AC;

/**
 * Access Control Error.
 *
 * @see Response
 *
 * Example XML:
 * @code
 * <ac:error name="login-error" module="username-password">
 *   <ac:text>Invalid password for username: ddt</ac:text>
 * </ac:error>
 * @endcode
 */
class Error extends Message {

  /**
   * Get the an exception for this error that may thrown.
   *
   * @return \Exception
   *   Return an Exception created with the given error
   */
  public function getException() {
    return new \Exception('AC Error: ' . $this->getName() . ' - ' . $this->getModule() . ' ' . $this->getText());
  }

  /**
   * Trigger an exception with this error.
   */
  public function triggerException() {
    if ($this->isError()) {
      throw $this->getException();
    }
  }

}
