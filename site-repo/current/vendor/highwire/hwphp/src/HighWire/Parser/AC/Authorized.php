<?php

namespace HighWire\Parser\AC;

/**
 * Privilege mapping for when reason is unknown.
 */
const PRIVILEGE_REASONS = [
  'urn:ac.highwire.org:guest:privilege' => 'anybody',
  'urn:ac.highwire.org:free:privilege' => 'free',
];

/**
 * Access Control Authorized Element.
 *
 * @see Request
 *
 * Example XML:
 * @code
 * <ac:authorized identity="b86d8682" privilege="8e036a3e"/>
 * @endcode
 */
class Authorized extends ACElement {

  /**
   * {@inheritdoc}
   */
  const GUEST_PRIVILEGE_REASON = 'anybody';

  /**
   * {@inheritdoc}
   */
  protected $default_xml = '<ac:authorized xmlns:ac="http://schema.highwire.org/Access" />';

  /**
   * Get the identity that was authorized to access the resource.
   *
   * @return \HighWire\Parser\AC\Identity|null
   *   The identity that was authorized, or NULL if the user was not authorized.
   */
  public function getIdentity() {
    $id = $this->getIdentityId();
    $elem = $this->dom->xpathSingle(".//ac:identity[@runtime-id='$id']");
    if (empty($elem)) {
      return NULL;
    }
    return new Identity($elem, $this->dom);
  }

  /**
   * Get the identity id attached to this authorized element.
   *
   * @return string
   *   The identity id.
   */
  public function getIdentityId() {
    return $this->elem->getAttribute('identity');
  }

  /**
   * Get the privilege that was used to authorize access.
   *
   * @return \HighWire\Parser\AC\Privilege|null
   *   The identity that was authorized, or NULL if the user was not authorized.
   */
  public function getPrivilege() {
    $id = $this->getPrivilegeId();
    $elem = $this->dom->xpathSingle("//ac:privilege[@runtime-id='$id']");
    if (empty($elem)) {
      return NULL;
    }
    return new Privilege($elem, $this->dom);
  }

  /**
   * Get the privilege id attached to this authorized element.
   *
   * @return string
   *   The privilege id.
   */
  public function getPrivilegeId() {
    return $this->elem->getAttribute('privilege');
  }

  /**
   * Get the reason attached to this authorized element.
   * 
   * Note that this will only return a result for some sort of FREE access, and will return NULL for purchased, institutional or individual access.
   *
   * @return string
   *   The reason access was granted, usually 'open-access', 'free', 'anybody', 'sample'.
   */
  public function getReason() {
    return $this->elem->getAttribute('reason');
  }

  /**
   * Get the reason for authorization.
   * 
   * This will preferentially look in the "reason" attribute,
   * and if there is nothing there, infer it by looking at the priviledge.
   *
   * @return string
   *   The reason access was granted, examples include:
   *   'open-access', 'free', 'anybody', 'sample', 'subscription', 'pay-per-view'.
   */
  public function inferReason() {
    $reason = $this->getReason();
    if ($reason) {
      return $reason;
    }

    foreach (PRIVILEGE_REASONS as $priv_id => $priv_reason) {
      if ($this->getPrivilegeId() == $priv_id) {
        return $priv_reason;
      }
    }

    // Some sort of instituional, individual, or purchased access.
    $priv = $this->getPrivilege();    
    if ($priv) {
      return $priv->getType();
    }
  }

  /**
   * Get the parent Authorization element.
   *
   * @return \HighWire\Parser\AC\Authorization|null
   *   The Authorization element under which this authorized element belongs.
   */
  public function parentAuthorization() {
    $elem = $this->dom->xpathSingle(".//parent::ac:authorization");
    if (empty($elem)) {
      return NULL;
    }
    return new Authorization($elem, $this->dom);
  }

}
