<?php

namespace HighWire\Parser\AC;

/**
 * Access Control Authorization Request.
 *
 * @see Request
 *
 * Example XML:
 * @code
 * <ac:authorize target="atom-resource" uri="/ddssh/34/4/523.atom" variant=".full.html" />
 * @endcode
 */
class Authorize extends ACElement {

  /**
   * {@inheritdoc}
   */
  protected $id_attribute = 'id';

  /**
   * {@inheritdoc}
   */
  protected $default_xml = '<ac:authorize xmlns:ac="http://schema.highwire.org/Access" />';

  /**
   * Set the target-type for authorize request.
   *
   * @param string $target
   *   Examples of target values include 'resource' and 'service',.
   *
   * @return self
   *   Return self for method chaining.
   */
  public function setTarget($target) {
    $this->setAttribute('target', $target);
    return $this;
  }

  /**
   * Get the target-type for authorize request.
   *
   * @return string
   *   Examples of target values include 'resource' and 'service',
   */
  public function getTarget() {
    return $this->getAttribute('target');
  }

  /**
   * Set the URI of the resource for the authorize request.
   *
   * @param string $uri
   *   Example: /ddssh/34/4/523.atom.
   *
   * @return self
   *   Return self for method chaining.
   *
   * @see setResourceTarget()
   */
  public function setUri($uri) {
    $this->setAttribute('uri', $uri);
    return $this;
  }

  /**
   * Get the URI of the resource for the authorize request.
   *
   * @return string
   *   Example: /ddssh/34/4/523.atom
   */
  public function getUri() {
    return $this->getAttribute('uri');
  }

  /**
   * Set the view of the resource for the authorize request.
   *
   * @param string $view
   *   Example: 'asbtract'.
   *
   * @return self
   *   Return self for method chaining.
   *
   * @see setResourceTarget()
   */
  public function setView($view) {
    $this->setAttribute('view', $view);
    return $this;
  }

  /**
   * Get the view of the resource for the authorize request.
   *
   * @return string
   *   Example: 'asbtract'
   */
  public function getView() {
    return $this->getAttribute('view');
  }

  /**
   * Set the resource target for the authorize request.
   * If no view is provided, a request will be made for all views.
   *
   * @param string $apath
   *   Example: /ddssh/34/4/523.atom.
   *
   * @param string $view
   *   Example: 'abstract'
   *   If no view is provided, a request will be made for all views.
   */
  public function setResourceTarget($apath, $view = NULL) {
    $this->setTarget('resource');
    $this->setUri($apath);
    if ($view !== NULL) {
      $this->setView($view);
    }
    else {
      $this->setView("*");
    }
    return $this;
  }

  /**
   * Given a Request, get all authorization responses that correspond
   * to this authorize request. A single authorize request may result
   * in more than one authorization response if no view was specified.
   * One authorization response will be provided for each view that the
   * resource has.
   *
   * @param Response $response
   *   Access Control response from the AC service.
   *
   * @return Authorization[]
   *   An array of Authorizations.
   *
   * @see getSingleAuthorization()
   */
  public function getAuthorizations(Response $response) {
    $id = $this->getId();
    $elems = $response->xpath(".//ac:authorization[@id='$id']");
    $results = [];
    foreach ($elems as $elem) {
      $results[] = new Authorization($elem, $response);
    }
    return $results;
  }

  /**
   * Given a Request, get a single authorization responses that correspond
   * to this authorize request. This method should only be called if you have
   * specified a view for the authorize request and know that only a single
   * authorization response will be returned.
   *
   * @param Response $response
   *   Access Control response from the AC service.
   *
   * @return Authorization|null
   *   A single Authorization.
   *
   * @see getAuthorizations()
   */
  public function getSingleAuthorization(Response $response) {
    $id = $this->getId();
    $elem = $response->xpathSingle(".//ac:authorization[@id='$id']");
    if (empty($elem)) {
      return NULL;
    }
    return new Authorization($elem, $response);
  }

}
