<?php

namespace HighWire\Parser\Logging;

use HighWire\Parser\DOMElementBase;
use HighWire\Parser\AC\Authorized;

/**
 * Authorization holds a single "<log:authorization>" element.
 *
 * For example:
 * @code
 * <log:authorization
 *   user-id="6"
 *   target="resource"
 *   authorized="true"
 *   authn-method="ip"
 *   authn-credentials="sigma"
 *   privilege-set="[8] Genetics - Journal"
 *   privilege-status="active"
 *   privilege-type="subscription"
 * />
 * @endcode
 */
class Authorization extends DOMElementBase {

  /**
   * Default XML to load if none is provided.
   *
   * @var string
   */
  protected $default_xml = '<log:authorization xmlns:log="http://schema.highwire.org/Service/Log" authorized="true" />';

  /**
   * {@inheritdoc}
   */
  protected $namespaces = [
    'log' => 'http://schema.highwire.org/Service/Log',
  ];

  /**
   * Get the user id.
   *
   * @return string
   *   The user id as defined in the 'user-id' attribute.
   *   Comes from (ac:identity.user-id)
   */
  public function userId(): string {
    return $this->getAttribute('user-id');
  }

  /**
   * Get the target
   *
   * @return string
   *   The target, generally 'resource'
   */
  public function target(): string {
    return $this->getAttribute('target');
  }

  /**
   * Check if the authorization is authorized
   *
   * @return bool
   *   TRUE if authorized, FALSE otherwise.
   */
  public function authorized(): bool {
    return $this->getAttribute('authorized') == 'true';
  }

  /**
   * Return the authentication method
   *
   * @return string
   *   The authentication mehod. (ac:identity.ac:credentials.method)
   */
  public function authnMethod(): string {
    return $this->getAttribute('authn-method');
  }

  /**
   * Return the authentication credentials
   *
   * @return string
   *   The authentication credentials. (ac:identity.ac:credentials.value)
   */
  public function authnCredientials(): string {
    return $this->getAttribute('authn-credentials');
  }

  /**
   * Return the privilege set.
   *
   * @return string
   *   The privilege set. (ac:identity.ac:privelege.privilege-set)
   */
  public function privilegeSet(): string {
    return $this->getAttribute('privilege-privilege-set');
  }

  /**
   * Return the privilege resource.
   *
   * @return string
   *   The privilege resource. (ac:identity.ac:privilege.resource)
   */
  public function privilegeResource(): string {
    return $this->getAttribute('privilege-resource');
  }

  /**
   * Return the privilege status.
   *
   * @return string
   *   The privilege status, generally 'active'. (ac:privilege.status)
   */
  public function privilegeStatus(): string {
    return $this->getAttribute('privilege-status');
  }

  /**
   * Return the privilege type.
   *
   * @return string
   *   The privilege type. (ac:privilege.type)
   */
  public function privilegeType(): string {
    return $this->getAttribute('privilege-type');
  }

  /**
   * Set the user id.
   *
   * @param string $user_id
   *   The user id to be stored in the 'user-id' attribute.
   *   Generally from (ac:identity.user-id).
   */
  public function setUserId(string $user_id) {
    $this->setAttribute('user-id', $user_id);
  }

  /**
   * Set the target.
   *
   * @param string $target
   *   The target to be stored in the 'target' attribute.
   *   Generally from (request.target).
   */
  public function setTarget(string $target) {
    $this->setAttribute('target', $target);
  }

  /**
   * Set if authorized.
   *
   * @param bool $authorized
   *   TRUE if authorized, FALSE otherwise.
   */
  public function setAuthorized(bool $authorized) {
    if ($authorized) {
      $this->setAttribute('authorized', 'true');
    }
    else {
      $this->setAttribute('authorized', 'false');
    }
  }

  /**
   * Set the authentication method.
   *
   * @param string $authn_method
   *   The authenticaiton method.
   *   Generally from (ac:identity.ac:credentials.method).
   */
  public function setAuthnMethod(string $authn_method) {
    $this->setAttribute('authn-method', $authn_method);
  }

  /**
   * Set the authentication credientials.
   *
   * @param string $authn_credientials
   *   The authenticaiton credentials.
   *   Generally from (ac:identity.ac:credentials.value)
   */
  public function setAuthnCredentials(string $authn_credientials) {
    $this->setAttribute('authn-credentials', $authn_credientials);
  }

  /**
   * Set the privilege set.
   *
   * @param string $privilege_set
   *   The privilege set.
   *   Generally from (ac:identity.ac:privilege.resource)
   */
  public function setPrivilegeSet(string $privilege_set) {
    $this->setAttribute('privilege-set', $privilege_set);
  }

  /**
   * Set the privilege resource.
   *
   * @param string $privilege_resource
   *   The privilege resource.
   *   Generally from (ac:identity.ac:privilege.resource)
   */
  public function setPrivilegeResource(string $privilege_resource) {
    $this->setAttribute('privilege-resource', $privilege_resource);
  }

  /**
   * Set the privilege status.
   *
   * @param string $privilege_status
   *   The privilege status.
   *   Generally from (ac:privilege.status)
   */
  public function setPrivilegeStatus(string $privilege_status) {
    $this->setAttribute('privilege-status', $privilege_status);
  }

  /**
   * Set the privilege type.
   *
   * @param string $privilege_type
   *   The privilege type.
   *   Generally from (ac:privilege.type)
   */
  public function setPrivilegeType(string $privilege_type) {
    $this->setAttribute('privilege-type', $privilege_type);
  }

  /**
   * Fill values from AC Authorized element.
   *
   * @param \HighWire\Parser\AC\Authorized $authorized
   *   An <ac:authorized> element object.
   */
  public function fillFromACAuthorized(Authorized $authorized) {
    $authorized->setAttribute('xmlns:log', 'http://schema.highwire.org/Service/Log');
    $authz = $authorized->parentAuthorization();
    $this->setTarget($authz->getTarget());
    $this->setAuthorized($authz->isAuthorized());
    if ($ident = $authorized->getIdentity()) {
      $this->setUserId($ident->getUserId());
      if ($cred = $ident->getCredentials()) {
        $this->setAuthnMethod($cred->getMethod());
        $this->setAuthnCredentials($cred->getValue());
      }
    }
    if ($priv = $authorized->getPrivilege()) {
      $this->setPrivilegeSet($priv->getPrivilegeSet());
      $this->setPrivilegeStatus($priv->getStatus());
      $this->setPrivilegeType($priv->getType());
      if ($priv_resource = $priv->getResource()) {
        $this->setPrivilegeResource($priv_resource);
      }
    }
  }

}
