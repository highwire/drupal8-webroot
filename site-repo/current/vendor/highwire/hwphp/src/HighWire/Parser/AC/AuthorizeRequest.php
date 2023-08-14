<?php

namespace HighWire\Parser\AC;

/**
 * Access Control Authorization Request.
 *
 * @see Request
 *
 * Example XML:
 * @code
 * <ac:authorize target="resource" uri="/ddssh/34/4/523.atom" scope="*"  />
 * @endcode
 */
class AuthorizeRequest extends ACElement {

  /**
   * {@inheritdoc}
   */
  protected $id_attribute = 'id';

  /**
   * {@inheritdoc}
   */
  protected $default_xml = '<ac:authorize-request xmlns:ac="http://schema.highwire.org/Access" />';

  /**
   * Set the target-type for authorize request.
   *
   * @param string $target
   *   Examples of target values include 'resource' and 'service',.
   *
   * @return self
   *   Return self for chaining
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
   * Set the scope of the request.
   *
   * This is new for AC 3.0.
   * Not Supported in older versions of the AC Service.
   *
   * @param string $scope
   *   The scope of the request.
   *   Examples could be 'online', 'download', '*', etc.
   *
   * @return self
   *   Return self for chaining
   */
  public function setScope($scope) {
    $this->setAttribute('scope', $scope);
    return $this;
  }

  /**
   * Get the scope of the authorization.
   *
   * @return string
   *   Retrurn the scope. Scope can be things like
   *   'online', 'download', '*' etc.
   */
  public function getScope() {
    return $this->getAttribute('scope');
  }

  /**
   * Set the URI of the resource for the authorize request.
   *
   * @param string $uri
   *   Example: /ddssh/34/4/523.atom.
   *
   * @return self
   *   For method chaining.
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

}
