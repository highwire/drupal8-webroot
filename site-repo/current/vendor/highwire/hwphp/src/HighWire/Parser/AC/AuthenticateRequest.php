<?php

namespace HighWire\Parser\AC;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Access Control request for authentication.
 *
 * @see Request
 *
 * Example XML:
 * @code
 * <ac:authenticate-request client-host="171.66.124.6" path="/" protocol="http" server-host="generic.highwire.org" server-port="80" method="GET" xml:base="http://generic.highwire.org/">
 *   <ac:header name="referer">http://generic.highwire.org/current.dtl</ac:header>
 *   <ac:cookie name="acceptsCookies">true</ac:cookie>
 *   <ac:parameter name="username">tolga1</ac:parameter>
 *   <ac:parameter name="code">highwire</ac:parameter>
 * </ac:authenticate-request>
 * @endcode
 */
class AuthenticateRequest extends ACElement {

  /**
   * {@inheritdoc}
   */
  protected $default_xml = '<ac:authenticate-request xmlns:ac="http://schema.highwire.org/Access" xml:base="http://generic.highwire.org" path="/" />';

  /**
   * Set the IP address of the host that the request
   * is coming in the 'client-host' attriute.
   *
   * @param string $ip
   *   The IP address of the requesting client.
   *   This should be the originating client, and
   *    not any intermediary loadbalancers or CDNs.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setClientHost($ip) {
    $this->setAttribute('client-host', $ip);
    return $this;
  }

  /**
   * Get the IP address of the requesting
   * client from the 'client-host' attriute.
   *
   * @return string
   *   The IP address of the requesting client.
   *   This should be the originating client,
   *   and not any intermediary loadbalancers or CDNs.
   */
  public function getClientHost() {
    return $this->getAttribute('client-host');
  }

  /**
   * Set the requested path in the 'path' attriute.
   *
   * @param string $path
   *   The requested path from the URL. eg "/content/1/2/3".
   *
   * @return self
   *   Return for method chaining.
   */
  public function setPath($path) {
    $this->setAttribute('path', $path);
    return $this;
  }

  /**
   * Get the requested path from the 'path' attriute.
   *
   * @return string
   *   The path of the authentication.
   */
  public function getPath() {
    return $this->getAttribute('path');
  }

  /**
   * Set the client request protocol in the 'protcol' attriute.
   *
   * @param string $protocol
   *   Should be 'http' or 'https'
   *   This should be the protocol used by the
   *   originating client, and not any intermediary
   *   loadbalancers or CDNs.
   *
   * @return self
   *   Return self for method chaining.
   */
  public function setProtocol($protocol) {
    if ($protocol != 'http' && $protocol != 'https') {
      throw new \Exception('Invalid Protocol. Got `' . $protocol . '`, expected `http` or `https`');
    }
    $this->setAttribute('protocol', $protocol);
    return $this;
  }

  /**
   * Get the client request protocol from the 'protcol' attriute.
   *
   * @return string
   *   Will be one of 'http' or 'https'
   */
  public function getProtocol() {
    return $this->getAttribute('protocol');
  }

  /**
   * Set the server hostname that the client connected to.
   *
   * @param string $host
   *   Hostname of the server, generally from the Host header.
   *
   * @return self
   *   Return self for method chaining.
   */
  public function setServerHost($host) {
    $this->setAttribute('server-host', $host);
    return $this;
  }

  /**
   * Get the server hostname that the client connected to.
   *
   * @return string
   *   Hostname of the server
   */
  public function getServerHost() {
    return $this->getAttribute('server-host');
  }

  /**
   * Set the port that the client connected to.
   *
   * @param string $port
   *   Port that the client connected to.
   *   This should be the port used by the originating client, and not any intermediary loadbalancers or CDNs.
   *
   * @return self
   *   Return self for method chaining.
   */
  public function setServerPort($port) {
    $this->setAttribute('server-port', $port);
    return $this;
  }

  /**
   * Get the server port that the client connected to.
   *
   * @return string
   *   Port that the client connected to.
   */
  public function getServerPort() {
    return $this->getAttribute('server-port');
  }

  /**
   * Set the HTTP Method that the client used to perform the request.
   *
   * @param string $method
   *   HTTP method (eg GET, POST) that the client used to perform the request.
   *
   * @return self
   *   Return self for method chaining.
   */
  public function setMethod($method) {
    $this->setAttribute('method', $method);
    return $this;
  }

  /**
   * Get the HTTP Method that the client used to perform the request.
   *
   * @return string
   *   HTTP method (eg GET, POST) that the client used to perform the request.
   */
  public function getMethod() {
    return $this->getAttribute('method');
  }

  /**
   * Set a header from the client.
   *
   * @param string $name
   *   Header name.
   * @param string $value
   *   Header value.
   *
   * @return self
   *   Return self for method chaining.
   *
   * @see setAllHeaders()
   */
  public function setHeader($name, $value) {
    $this->append("<ac:header name='$name'>$value</ac:header>");
    return $this;
  }

  /**
   * Get a header.
   *
   * @param string $name
   *   Header name.
   *
   * @return string|null
   *   Header value, or NULL if header does not exist
   *
   * @see getAllHeaders()
   */
  public function getHeader($name) {
    $elem = $this->xpathSingle(".//ac:header[@name='$name']");
    if (empty($elem)) {
      return NULL;
    }
    return $elem->nodeValue;
  }

  /**
   * Set all headers from the client.
   *
   * @param array $headers
   *   Key-value array of headers.
   *
   * @return self
   *   Return self for method chaining
   *
   * @see setHeader()
   */
  public function setAllHeaders(array $headers) {
    foreach ($headers as $key => $value) {
      $this->setHeader($key, $value);
    }
    return $this;
  }

  /**
   * Get all headers.
   *
   * @return array
   *   Key-value array of headers
   *
   * @see getHeader()
   */
  public function getAllHeaders() {
    $headers = [];
    foreach ($this->xpath('.//ac:header') as $elem) {
      $header[$elem->getAttribute('name')] = $elem->nodeValue;
    }
    return $headers;
  }

  /**
   * Set a cookie from the client.
   *
   * @param string $name
   *   Cookie name.
   * @param string $value
   *   Cookie value. The value should not be encoded in any way.
   *
   * @return self
   *   Return self for method chaining.
   *
   * @see setAllCookies()
   */
  public function setCookie($name, $value) {
    $cookieval = urlencode($value);
    $this->append("<ac:cookie name='$name'>$cookieval</ac:cookie>");
    return $this;
  }

  /**
   * Get a cookie.
   *
   * @param string $name
   *   Cookie name.
   *
   * @return string|null
   *   Cookie value, or NULL if cookie does not exist
   *
   * @see getAllCookies()
   */
  public function getCookie($name) {
    $elem = $this->xpathSingle(".//ac:cookie[@name='$name']");
    if (empty($elem)) {
      return NULL;
    }
    return urldecode($elem->nodeValue);
  }

  /**
   * Set all cookies from the client.
   *
   * @param array $cookies
   *   Key-value array of cookies.
   *
   * @return self
   *   Return self for method chaining.
   *
   * @see setCookie()
   */
  public function setAllCookies(array $cookies) {
    foreach ($cookies as $key => $value) {
      $this->setCookie($key, $value);
    }
    return $this;
  }

  /**
   * Get all cookies.
   *
   * @return array
   *   Key-value array of cookies
   *
   * @see getCookie()
   */
  public function getAllCookies() {
    $cookies = [];
    foreach ($this->xpath('.//ac:cookie') as $elem) {
      $cookies[$elem->getAttribute('name')] = urldecode($elem->nodeValue);
    }
    return $cookies;
  }

  /**
   * Set a parameter (eg from a POST request)
   *
   * @param string $name
   *   Parameter name.
   * @param string $value
   *   Parameter value. The value should not be encoded in any way.
   *
   * @return self
   *   Return self for method chaining
   *
   * @see setAllParameters()
   */
  public function setParameter($name, $value) {
    $this->append("<ac:parameter name='$name'>$value</ac:parameter>");
    return $this;
  }

  /**
   * Get a parameter.
   *
   * @param string $name
   *   Parameter name.
   *
   * @return string|null
   *   Parameter value, or NULL if parameter does not exist
   *
   * @see getAllParameters()
   */
  public function getParameter($name) {
    $elem = $this->xpathSingle(".//ac:parameter[@name='$name']");
    if (empty($elem)) {
      return NULL;
    }
    return $elem->nodeValue;
  }

  /**
   * Set all parameters (eg from a POST request)
   *
   * @param array $parameters
   *   Key-value array of parameters.
   *
   * @return self
   *   Return self for method chaining.
   *
   * @see setParameter()
   */
  public function setAllParameters(array $parameters) {
    foreach ($parameters as $key => $value) {
      $this->setParameter($key, $value);
    }
    return $this;
  }

  /**
   * Get all parameters.
   *
   * @return array
   *   Key-value array of parameters
   *
   * @see getParameter()
   */
  public function getAllParameters() {
    $parameters = [];
    foreach ($this->xpath('.//ac:parameter') as $elem) {
      $parameters[$elem->getAttribute('name')] = $elem->nodeValue;
    }
    return $parameters;
  }

  /**
   * Set login parameters to authenticate a login request.
   *
   * @param string $username
   *   Username provided by the client.
   * @param string $password
   *   Password provided by the client.
   *   The password should not be encoded in any way.
   *
   * @return self
   *   Return self for method chaining.
   */
  public function setLoginParameters($username, $password) {
    $this->setParameter('username', $username);
    $this->setParameter('code', $password);
    return $this;
  }

  /**
   * Fill in values from a symfony request object.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Symfony Request object
   *   As an example, in Drupal you could pass Drupal::request()
   *
   * @return self
   *   Return self for method chaining.
   *
   *   Example Usage:
   *
   * @code
   *   # Using Symfony
   *   use Symfony\Component\HttpFoundation\Request;
   *   $authn_request = new AuthenticateRequest();
   *   $authn_request->fillFromRequest(Request::createFromGlobals());
   *
   *   # Using Drupal
   *   use Drupal;
   *   $authn_request = new AuthenticateRequest();
   *   $authn_request->fillFromRequest(Drupal::request());
   * @endcode
   */
  public function fillFromRequest(SymfonyRequest $request) {
    $this->setClientHost($request->getClientIp());
    $this->setPath($request->getPathInfo());
    $this->setProtocol($request->isSecure() ? 'https' : 'http');
    $this->setServerHost($request->getHost());
    $this->setServerPort($request->getPort());
    $this->setMethod($request->getMethod());
    foreach ($request->headers->all() as $key => $value) {
      $this->setHeader($key, $value);
    }
    foreach ($request->cookies->all() as $key => $value) {
      $this->setCookie($key, $value);
    }
    foreach ($request->query->all() as $key => $value) {
      $this->setParameter($key, $value);
    }

    return $this;
  }

}
