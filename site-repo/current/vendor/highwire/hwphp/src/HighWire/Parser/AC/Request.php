<?php

namespace HighWire\Parser\AC;

use BetterDOMDocument\DOMDoc;

/**
 * Access Control Request.
 *
 * @link http://confluence.highwire.org/pages/viewpage.action?pageId=7768947
 * @link http://developer.highwire.org/ACWebapp/doc/
 * @link http://developer.highwire.org/ACWebapp/doc/h20_ac.xhtml
 */
class Request extends DOMDoc {

  /**
   * Flag to indicate if this is an admin request.
   *
   * @var boolean
   */
  protected $adminRequest = FALSE;

  /**
   * Create an ACRequest object.
   *
   * @param string $ac_request_xml
   *   Optionally pass an entire request object string.
   */
  public function __construct($ac_request_xml = '') {
    if (!empty($ac_request_xml)) {
      parent::__construct($ac_request_xml);
    }
    else {
      parent::__construct('<ac:runtime-request xmlns:ac="http://schema.highwire.org/Access" xmlns:gen="http://schema.highwire.org/Site/Generator"></ac:runtime-request>');
    }

    $this->registerNamespace('ac', 'http://schema.highwire.org/Access');
    $this->registerNamespace('gen', 'http://schema.highwire.org/Site/Generator');
  }

  /**
   * Make an admin request.
   * This will tell AC to return full access and not try and authorize the user.
   *
   * @note this is not supported yet.
   *   Waiting on https://jira.highwire.org/browse/PLATFORM1-961
   *
   * @param bool $bool
   *   True or False depeding on if this is an admin request.
   *
   * @return self
   *   Return self for method chaining.
   */
  public function setAdminRequest($bool) {
    $this->adminRequest = $bool;
    return $this;
  }

  /**
   * A request may only have a single <authenticate-request>, calling
   * this method will replace any existing <authenticate-request>.
   *
   * @note If authenticate request is set on a request to AC 3.0 service
   * the service will throw a 500 error.
   *
   * @param AuthenticateRequest $authn
   *   An authentication request parser object.
   *
   * @return \HighWire\Parser\AC\Request
   *   Return for method chaining.
   *
   * @see getAuthenticateRequest()
   */
  public function setAuthenticateRequest(AuthenticateRequest $authn) {
    if ($elem = $this->xpathSingle('//ac:authenticate-request')) {
      $authn->elem = $this->replace($elem, $authn->elem);
    }
    else {
      $authn->elem = $this->append($authn->elem);
    }
    $authn->setDom($this);
    return $this;
  }

  /**
   * Get the <authenticate-request> element on the request.
   *
   * @return AuthenticateRequest|null
   *   Return the authentate request element or null if it's not found.
   */
  public function getAuthenticateRequest() {
    $elem = $this->xpathSingle("//ac:authenticate-request");

    if (empty($elem)) {
      return NULL;
    }

    return new AuthenticateRequest($elem, $this);
  }

  /**
   * Add an ACElement to the root of the AC Request.
   *
   * @param ACElement $elem
   *   An ACElement to append the root of the AC Request.
   *
   * @return \HighWire\Parser\AC\Request
   *   Return self for method chaining.
   */
  public function addACElement(ACElement $elem) {
    $elem->elem = $this->append($elem->elem);
    $elem->setDOM($this);
    return $this;
  }

  /**
   * Get the <authorize> element on the request identified by
   * the given runtime id.
   *
   * @return Authorize
   *   An authorize parser element.
   */
  public function getAuthorize($id) {
    $elem = $this->xpathSingle("//ac:authorize[@id='$id']");
    if (empty($elem)) {
      return NULL;
    }
    return new Authorize($elem, $this);
  }

  /**
   * Get all <authorize> elements on the request.
   *
   * @return Authorize[]
   *   An array of all authorize parser request objects.
   */
  public function getAllAuthorizeRequests() {
    $authzs = [];
    foreach ($this->xpath('//ac:authorize') as $elem) {
      $authz = new Authorize($elem, $this);
      if ($id = $authz->getId()) {
        $authzs[$id] = $authz;
      }
      else {
        $authz[] = $authz;
      }
    }
    return $authzs;
  }

  /**
   * Create an authorize request for a given apath.
   *
   * @param string $apath
   *   Atom-path for the resource you wish to authorize.
   *
   * @return \HighWire\Parser\AC\Request
   *   Return self for chaining
   */
  public function authorizeApath($apath) {
    $this->authorizeApaths([$apath]);
    return $this;
  }

  /**
   * Create an authorize request for given apaths.
   *
   * @param string[] $apaths
   *   An array of atom-path's for the resource you wish to authorize.
   *
   * @return \HighWire\Parser\AC\Request
   *   Return self for chaining
   */
  public function authorizeApaths(array $apaths) {
    foreach ($apaths as $apath) {
      $authz = new AuthorizeRequest();
      $authz->setTarget('resource');
      $authz->setScope('*');
      $authz->setUri($apath);
      $this->addACElement($authz);
    }
    return $this;
  }

  /**
   * Add an authorizing entitlements element to the request.
   *
   * @param string $type
   *   The type attribute.
   * @param string $data
   *   The data to set as the node value.
   *
   * @return \HighWire\Parser\AC\Request
   *   Return self for chaining.
   */
  public function addAuthorizingEntitlements($type, $data = '') {
    $entitlement = new AuthorizingEntitlements();
    $entitlement->setType($type);

    if (!empty($data)) {
      $entitlement->setData($data);
    }

    $this->addACElement($entitlement);

    return $this;
  }

  /**
   * Get authorizing entitlements.
   *
   * @param string $type
   *   Optionally filter by the type.
   *
   * @return AuthorizingEntitlements[]
   *   An array of AuthorizingEntitlements or an empty array if none are found.
   */
  public function getAuthorizingEntitlements($type = '') {
    $auth_ents = [];
    if (empty($type)) {
      $xpath = '//ac:authorizing-entitlements';
    }
    else {
      $xpath = '//ac:authorizing-entitlements[@type="' . $type . '"]';
    }

    $results = $this->xpath($xpath);

    if (!empty($results)) {
      foreach ($results as $elem) {
        $auth_ent = new AuthorizingEntitlements($elem, $this);
        $auth_ents[] = $auth_ent;
      }
    }

    return $auth_ents;
  }

  /**
   * Create a resource authorization request.
   *
   * @note This method is only supported by AC 2.0 and lower.
   *
   * @param string $apath
   *   Atom-path for the resource you wish to authorize.
   *
   * @return self
   *   Return self for method chaining.
   */
  public function createResourceAuthorize($apath) {
    $authz = new Authorize();
    $authz->setResourceTarget($apath);
    $this->addACElement($authz);
    return $this;
  }

  /**
   * {@inheritDoc}
   * @see \BetterDOMDocument\DOMDoc::out()
   */
  public function out($context = NULL) {
    // Before serializing to a string deal with admin requests.
    // This has to be done right before serialzation because
    // certain elements need to be removed before the request is sent.
    if ($this->adminRequest) {
      $this->setupAdminRequest();
    }

    return parent::out($context);
  }

  /**
   * Adjust the request to make an admin request.
   */
  public function setupAdminRequest() {
    // If we are making an admin request ac:authenticate-request
    // and ac:authorizing-entitlements needs to be removed.
    $remove_xpaths = ['//ac:authorizing-entitlements', '//ac:authenticate-request'];
    $this->remove($remove_xpaths);


    // Now add the authorizeids element.
    $auth_ids = new AuthenticateIds();
    $identifier = new Identifier();
    $identifier->setAdmin("true");
    $identifier->setLogin('guest');
    $auth_ids->addIdentifier($identifier);
    $this->append($auth_ids->elem);
  }

}
