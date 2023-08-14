<?php

namespace HighWire\Parser\AC;

/**
 * Authenticate ids allows you to force the user to be a specific user.
 */
class AuthenticateIds extends ACElement {
  /**
   * {@inheritdoc}
   */
  protected $default_xml = '<ac:authenticate-ids xmlns:ac="http://schema.highwire.org/Access" />';

  /**
   * Add an identifier.
   *
   * @param Identifier $identifier
   *   An identifier dom element.
   *
   * @return AuthenticateIds
   *   Return self for method chaining.
   */
  public function addIdentifier(Identifier $identifier) {
    $this->append($identifier->elem);
    return $this;
  }

}
