<?php

namespace HighWire\Parser\AC;

/**
 * An ac identifier element.
 */
class Identifier extends ACElement {

  protected $default_xml = '<ac:identifier xmlns:ac="http://schema.highwire.org/Access" />';

  /**
   * Set the type attribute.
   *
   * @param string $type
   *   The type attribute.
   *
   * @return \HighWire\Parser\AC\Identifier
   *   Return this for chaining.
   */
  public function setType($type): Identifier {
    $this->setAttribute('type', $type);
    return $this;
  }

  /**
   * Set the login parameters.
   *
   * @param string $user
   *   The user to force access against.
   *
   * @return \HighWire\Parser\AC\Identifier
   *   Return this for chaining.
   */
  public function setLogin($user): Identifier {
    $this->setAttribute('login', $user);
    return $this;
  }

  /**
   * Set weather this user is admin or not.
   *
   * @param string $bool
   *   A string 'true' or 'false'.
   *
   * @return \HighWire\Parser\AC\Identifier
   *   Return this for chaining.
   */
  public function setAdmin($bool): Identifier {
    $this->setAttribute('admin', $bool);
    return $this;
  }

}
