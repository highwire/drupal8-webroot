<?php

namespace HighWire\Parser\AC;

use BetterDOMDocument\DOMDoc;
use HighWire\Clients\ResponseTrait;
use HighWire\Clients\HWResponseInterface;

/**
 * Access Control Response.
 *
 * @link http://confluence.highwire.org/pages/viewpage.action?pageId=7768947
 * @link http://developer.highwire.org/ACWebapp/doc/
 * @link http://developer.highwire.org/ACWebapp/doc/h20_ac.xhtml
 */
class Response extends DOMDoc implements HWResponseInterface {

  use ResponseTrait;

  /**
   * {@inheritdoc}
   */
  public $ns = [
    'ac' => 'http://schema.highwire.org/Access',
    'ec' => 'http://schema.highwire.org/E-Commerce',
  ];

  /**
   * {@inheritdoc}
   */
  public function getData() {
    return $this;
  }

  /**
   * Get the <authentication> element from the response.
   *
   * @return Authentication|null
   *   An authentication element or null if it's not found.
   */
  public function getAuthentication() {
    $elem = $this->xpathSingle('//ac:authentication');
    if (empty($elem)) {
      return NULL;
    }
    return new Authentication($elem, $this);
  }

  /**
   * Get all <authorization> elements from the response.
   *
   * @return Authorization[]
   *   An array of authorization elements.
   */
  public function getAllAuthorizations() {
    $authzs = [];
    foreach ($this->xpath('//ac:authorization') as $elem) {
      $authzs[] = new Authorization($elem, $this);
    }
    return $authzs;
  }

  /**
   * Get the <authorization> element from the response identified by the provided runtime id.
   *
   * @return Authorization|null
   *   An authorization element or null if it's not found.
   */
  public function getAuthorization($id) {
    $elem = $this->xpathSingle("//ac:authorization[@id='$id']");
    if (empty($elem)) {
      return NULL;
    }
    return new Authorization($elem, $this);
  }

  /**
   * Get the <authorization> element from the response identified by an apath and a view.
   *
   * Note that this only works for AC 2.0 or below.
   *
   * @param string $apath
   *   Atom-path for the resource.
   * @param string $view
   *   View for the resource.
   *
   * @return Authorization|null
   *   An authorization element or null if it's not found.
   */
  public function getResourceAuthorization($apath, $view) {
    $elem = $this->xpathSingle("//ac:authorization[@uri='$apath' and @view='$view']");
    if (empty($elem)) {
      return NULL;
    }

    return new Authorization($elem, $this);
  }

  /**
   * Get the all <authorization> elements from the response identified by the apath.
   *
   * @param string $apath
   *   Atom-path for the resource.
   *
   * @return Authorization[]
   *   An array of authorization elements.
   */
  public function getResourceAuthorizations($apath) {
    $results = [];
    $elems = $this->xpath("//ac:authorization[@uri='$apath']");

    foreach ($elems as $elem) {
      $authz = new Authorization($elem, $this);
      // AC 3.0.
      if ($scope = $authz->getScope()) {
        $results[$scope] = $authz;
      }
      // AC 2.0.
      elseif ($view = $authz->getView()) {
        $results[$view] = $authz;
      }
      else {
        $results[] = $authz;
      }
    }

    return $results;
  }

  /**
   * Get the all <http-response> element from the response.
   *
   * This should be used to set headers and cookies for the
   * end-user's client.
   *
   * @return HTTPResponse|null
   *   An http response element of null if it's not found.
   */
  public function getHTTPResponse() {
    $elem = $this->xpathSingle("//ac:http-response");
    if (empty($elem)) {
      return NULL;
    }
    return new HTTPResponse($elem, $this);
  }

  /**
   * Find an authorization element with the given properties.
   *
   * @param string $id
   *   The id to check access for. Usually this is the apath.
   * @param string $role
   *   The role corresponds a particular context
   *   in which content is being viewed. Generally there will
   *   be a one to one relationship between the markup profile
   *   and this role, but not always. For example the markup
   *   profile could be bmj-full-text, but the role
   *   to check access on would be full-text.
   * @param string $type
   *   The content type of the resource that you are checking access against.
   *   The pdf and full-text html will generally have the same role,
   *   because they are representing the same version of the content,
   *   but their format is different.
   * @param string $scope
   *   The scope of the granted access. An example scope
   *   would be something like online vs download. The scope may
   *   matter on some sites and others it has no meaning.
   *   It's up to the site to decide
   *   this meaning.
   * @param string $lang
   *   The resource authorized language.
   * @param string $target
   *   The target of the access check.
   *
   * @return Authorization|null
   *   Return the authorization, or NULL if none could be found.
   *
   * @see https://jira.highwire.org/browse/PLATFORM1-860
   */
  public function findAuthorization(
    $id,
    $role,
    $type = 'application/xhtml+xml',
    $scope = '',
    $lang = 'en',
    $target = 'variant'
  ) {
    $authorizations = $this->getAllAuthorizations();
    if (empty($authorizations)) {
      return NULL;
    }

    foreach ($authorizations as $authorization) {
      if ($authorization->getUri() != $id) {
        continue;
      }

      if (!empty($scope)) {
        // Authorization was asked within a certain scope.
        // This authorization doesn't have that scope, so skip it.
        if ($authorization->getScope() != $scope) {
          continue;
        }
      }

      $scopeds = $authorization->getAuthorizedScoped();

      if (!empty($scopeds)) {
        foreach ($scopeds as $scoped) {
          // Check type, role, target, and lang if possible.
          if (
            (!empty($type) && $type != $scoped->getType()) ||
            (!empty($target) && $target != $scoped->getTarget()) ||
            (!empty($lang) && $lang != $scoped->getLanguage()) ||
            (!empty($role) && $role != $scoped->getRole())
          ) {
            continue;
          }

          // If we made here, we found the authz we are looking for
          return $authorization;
        }
      }
      else {
        // Some authz doesn't have ac:scoped element, check for authorization
        // using ac:authorization scope attribute.
        if ($authorization->getScope() === $scope) {
          return $authorization;
        }
      }
    }
  }

  /**
   * Check if a user has access to content.
   *
   * @param string $id
   *   The id to check access for. Usually this is the apath.
   * @param string $role
   *   The role corresponds a particular context
   *   in which content is being viewed. Generally there will
   *   be a one to one relationship between the markup profile
   *   and this role, but not always. For example the markup
   *   profile could be bmj-full-text, but the role
   *   to check access on would be full-text.
   * @param string $type
   *   The content type of the resource that you are checking access against.
   *   The pdf and full-text html will generally have the same role,
   *   because they are representing the same version of the content,
   *   but their format is different.
   * @param string $scope
   *   The scope of the granted access. An example scope
   *   would be something like online vs download. The scope may
   *   matter on some sites and others it has no meaning.
   *   It's up to the site to decide
   *   this meaning.
   * @param string $lang
   *   The resource authorized language.
   * @param string $target
   *   The target of the access check.
   *
   * @return bool
   *   True or False depending on if the logged in user has access.
   *
   * @see https://jira.highwire.org/browse/PLATFORM1-860
   */
  public function userHasAccess(
    $id,
    $role,
    $type = 'application/xhtml+xml',
    $scope = '',
    $lang = 'en',
    $target = 'variant'
  ) {

    // If there has been an error fetching the response, grant access
    if ($this->hasResponse() && $this->getStatusCode() != 200) {
      return TRUE;
    }

    $access = FALSE;
    $authz = $this->findAuthorization($id, $role, $type, $scope, $lang, $target);
    if ($authz) {
      $access = $authz->isAuthorized();
    }

    return $access;
  }

}
