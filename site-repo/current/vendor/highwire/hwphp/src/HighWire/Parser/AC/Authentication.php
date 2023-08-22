<?php

namespace HighWire\Parser\AC;

/**
 * Access Control Authentication.
 *
 * @see Response
 *
 * Example XML:
 * @code
 * <ac:authentication>
 *   <ac:identity runtime-id="urn:ac.highwire.org:guest:identity" type="guest">
 *   <ac:identity runtime-id="70c33862-26d4-404f-a9a1-a4cc2916016a" type="individual" user-id="1911" subcode="dupjnl_subdev" customer-number="TOLGA1" display-name="olga Biasotti" email="obiasotti@highwire.org">
 *   <ac:privilege runtime-id="5e47762d-1532-4b8d-b612-8a5166a8b51c" type="subscription" user-id="1911" status="ACTIVE" expiration="9999-12-31T23:59:59.999-08:00" privilege-set="MALL" />
 *     <ac:credentials method="username">tolga1</ac:credentials>
 *   </ac:identity>
 *   <ac:message name="logged-in" module="username-password" />
 * </ac:authentication>
 * @endcode
 */
class Authentication extends ACElement {

  /**
   * Get the identity with the given ID.
   *
   * @param string $id
   *   Unique ID for the identity. Note that this identity is unique to this response, and will change across subsequent requests / responses.
   *
   * @return Identity|null
   *   The identity, or NULL if the identity is not found.
   */
  public function getIdentity($id) {
    $elem = $this->xpathSingle(".//ac:identity[@runtime-id='$id']");
    if (empty($elem)) {
      return NULL;
    }
    return new Identity($elem, $this->dom);
  }

  /**
   * Get all identities.
   *
   * @return Identity[]
   *   Array of identities keyed by ID.
   */
  public function getAllIdentities() {
    $idents = [];
    foreach ($this->xpath('.//ac:identity') as $elem) {
      $ident = new Identity($elem, $this->dom);
      if ($id = $ident->getId()) {
        $idents[$id] = $ident;
      }
      else {
        $idents[] = $ident;
      }
    }
    return $idents;
  }

  /**
   * Get all messages to be displayed to user.
   *
   * @return \HighWire\Parser\AC\Message[]
   *   An array of AC message elements.
   */
  public function getAllMessages(): array {
    $messages = [];
    foreach ($this->xpath('ac:message') as $elem) {
      $messages[] = new Message($elem, $this->dom);
    }
    return $messages;
  }

  /**
   * Get all errors to be displayed to user.
   *
   * @return \HighWire\Parser\AC\Error[]
   *   Reuturns an array of AC error elements.
   */
  public function getAllErrors() {
    $messages = [];
    foreach ($this->xpath('ac:error') as $elem) {
      $messages[] = new Error($elem, $this->dom);
    }
    return $messages;
  }

  /**
   * Get a message.
   *
   * @param string $name
   *   The message name.
   * @param string $module
   *   The AC Service module (not drupal module) that generated the message.
   *
   * @return \HighWire\Parser\AC\Message|null
   *   The message, or NULL if no message is found.
   */
  public function getMessage($name, $module = FALSE): Message {
    if ($module !== FALSE) {
      $elem = $this->xpathSingle(".//ac:message[@name='$name' and @module='$module']");
    }
    else {
      $elem = $this->xpathSingle(".//ac:message[@name='$name']");
    }

    if (empty($elem)) {
      return NULL;
    }
    return new Message($elem, $this->dom);
  }

  /**
   * Get an error.
   *
   * @param string $name
   *   The error name.
   * @param string $module
   *   The AC Service module (not drupal module) that generated the error.
   *
   * @return \HighWire\Parser\AC\Error|null
   *   The error, or NULL if no error is found.
   */
  public function getError($name, $module = FALSE): Error {
    if ($module !== FALSE) {
      $elem = $this->xpathSingle(".//ac:error[@name='$name' and @module='$module']");
    }
    else {
      $elem = $this->xpathSingle(".//ac:error[@name='$name']");
    }
    if (empty($elem)) {
      return NULL;
    }
    return new Error($elem, $this->dom);
  }

  /**
   * Check if a login was successful.
   *
   * @return bool
   *   TRUE if login was successful, FALSE if login was not successful
   */
  public function isLoginOK() {
    return !empty($this->getMessage('logged-in'));
  }

  /**
   * Get the identity that was logged in.
   *
   * @param string $username
   *   The username used to log in. Optional.
   *
   * @return \HighWire\Parser\AC\Identity|null
   *   Identity if login was successful, NULL if
   *   login was not successful or a logged in identity
   *   with the provided username was not found.
   */
  public function getLoginIdentity($username = ''): Identity {
    if ($username) {
      $cred = $this->xpathSingle(".//ac:credentials[@method='username' and text()='$username']");
    }
    else {
      $cred = $this->xpathSingle(".//ac:credentials[@method='username']");
    }
    if (empty($cred)) {
      return NULL;
    }
    return new Identity($cred->parentNode, $this->dom);
  }

}
