<?php

namespace HighWire\Parser\AC;

/**
 * Access Control Authorization.
 *
 * @see Response
 *
 * Example XML:
 * AC 3.0
 * @code
 * <ac:authorization target="resource" uri="/freebird2/book/978-0-8261-1749-6/part/part01/chapter/3-8.atom" scope="download">
 *   <ac:unauthorized reason="access-denied" />
 * </ac:authorization>
 *
 * <ac:authorization target="resource" uri="/freebird2/book/978-0-8261-1749-6/part/part01/chapter/3-8.atom" scope="download">
 *   <ac:authorized identity="b9b7e01c" privilege="43ec28af"/>
 *   <ac:scoped target="variant" role="full-text" type="application/pdf" lang="en"/>
 *   <ac:scoped target="variant" role="source" type="application/xml" lang="en"/>
 * </ac:authorization>
 * @endcode
 *
 * AC 2.0 and below
 * @code
 * <ac:authorization target="service" id="12345">
 *   <ac:unauthorized reason="access-denied" />
 * </ac:authorization>
 *
 * <ac:authorization target="resource" uri="/ddssh/34/4/523.atom" view="abstract">
 *   <ac:authorized identity="70c33862-26d4-404f-a9a1-a4cc2916016a" privilege="5e47762d-1532-4b8d-b612-8a5166a8b51c" />
 * </ac:authorization>
 * @endcode
 */
class Authorization extends ACElement {

  /**
   * {@inheritdoc}
   */
  protected $id_attribute = 'id';

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
   * Get the scope of the authorization.
   *
   * @return string
   *   Get the scope of an authorization.
   */
  public function getScope() {
    return $this->getAttribute('scope');
  }

  /**
   * Get the URI of the resource for the authorization response.
   *
   * @return string
   *   Example: /ddssh/34/4/523.atom
   */
  public function getUri() {
    return $this->getAttribute('uri');
  }

  /**
   * Get the view of the resource for the authorization response.
   *
   * @note view is only supported in AC 2.0 or lower.
   *
   * @return string
   *   Example: 'asbtract'
   */
  public function getView() {
    return $this->getAttribute('view');
  }

  /**
   * Is the user authorized to access the resource?
   *
   * @return bool
   *   TRUE if the user is authorized, FALSE if the user is not authorized.
   */
  public function isAuthorized() {
    return !empty($this->xpathSingle('.//ac:authorized'));
  }

  /**
   * Get the reason the user is not authorized.
   *
   * @return string
   *   The reason the user was not authorized,
   *   or NULL if the user was authorized.
   */
  public function unauthorizedReason() {
    $reason = $this->xpathSingle('.//ac:unauthorized/@reason');
    if (empty($reason)) {
      return NULL;
    }
    return $reason->nodeValue;
  }

  /**
   * Get all scopes for the authorization.
   *
   * @return \HighWire\Parser\AC\Scoped[]
   *   The scopes for this authorization request.
   */
  public function getAuthorizedScoped(): array {
    $scopeds = [];

    foreach ($this->xpath('.//ac:scoped') as $elem) {
      $scopeds[] = new Scoped($elem, $this->dom);
    }

    return $scopeds;
  }

  /**
   * Get the identity that was authorized to access the resource.
   * 
   * Note that more than one identity may have been used, if you want
   * all identities, use getAuthorized() and loop through all Authorized.
   * 
   * @return \HighWire\Parser\AC\Identity|null
   *   The identity that was authorized, or NULL if the user was not authorized.
   */
  public function getAuthorizedIdentity() {
    $ident = $this->xpathSingle('.//ac:authorized/@identity');
    if (empty($ident)) {
      return NULL;
    }
    $id = $ident->nodeValue;
    if (empty($this->dom)) {
      return NULL;
    }

    $elem = $this->dom->xpathSingle(".//ac:identity[@runtime-id='$id']");
    if (empty($elem)) {
      return NULL;
    }
    return new Identity($elem, $this->dom);
  }

  /**
   * Get the priviledge which caused the authorization to succeed.
   * 
   * Note that more than one priviledge may have been used, if you want
   * all priviledges, use getAuthorized() and loop through all Authorized.
   *
   * @return \HighWire\Parser\AC\Privilege|null
   *   The priviledge caused the authorization
   *   to succeed, or NULL if the user was not authorized.
   */
  public function getAuthorizedPrivilege() {
    $priv = $this->xpathSingle('.//ac:authorized/@privilege');
    if (empty($priv)) {
      return NULL;
    }

    $identity = $this->getAuthorizedIdentity();
    if (empty($identity)) {
      return NULL;
    }

    return $identity->getPrivilege($priv->nodeValue);
  }

  /**
   * Get the license which caused the authorization to succeed.
   *
   * @return string|null
   *   The license which caused the authorization
   *   to succeed, or NULL if the user was not authorized.
   */
  public function getAuthorizedLicense() {
    $priv = $this->xpathSingle('.//ac:authorized/@privilege');
    if (empty($priv)) {
      return NULL;
    }

    $identity = $this->getAuthorizedIdentity();
    if (empty($identity)) {
      return NULL;
    }

    return $identity->getPrivilege($priv->nodeValue);
  }


  /**
   * Get all authorized elements under this Authorization.
   *
   * @param string|null $role
   *   The role to filter authorizations by or null to return all.
   *
   * @return \HighWire\Parser\AC\Authorized[]
   *   The authorized element.
   */
  public function getAuthorized(string $role = NULL) {
    $authorized = [];

    if (empty($role)) {
      $xpath = ".//ac:authorized";
    }
    else {
      $xpath = "//ac:authorization/ac:scoped[@role='$role']/preceding-sibling::ac:authorized";
    }

    $elems = $this->dom->xpath($xpath, $this->elem);
    foreach ($elems as $elem) {
      $authorized[] = new Authorized($elem, $this->dom);
    }
    return $authorized;
  }

}
