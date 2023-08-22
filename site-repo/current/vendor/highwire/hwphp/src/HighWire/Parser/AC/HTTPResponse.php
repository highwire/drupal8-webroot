<?php

namespace HighWire\Parser\AC;

use Symfony\Component\HttpFoundation\Cookie;

/**
 * Access Control HTTP Response.
 *
 * @see Response
 *
 * Example XML:
 * @code
 * <ac:http-response>
 *   <ac:cookie name="login" age-maximum="-1" version="0" path="/">authn%3A1379965592%3A%7BAES%7DvndbOywqhL7FOAMAHu%2FrnwLlD5ojDXbjRL81odsT4qvOm84iyn2PziNw%2B4nTXDDHPxkP%2F1tYjEJ5k5zdvPShLw%3D%3D%3ACqOeaQRcW%2BbL0khietufHw%3D%3D</ac:cookie>
 * </ac:http-response>
 * @endcode
 */
class HTTPResponse extends ACElement {

  /**
   * Get a cookie that should be set.
   *
   * @param string $name
   *   The name of the cookie.
   *
   * @return \Symfony\Component\HttpFoundation\Cookie|false
   *   A cookie object.
   */
  public function getCookie($name) {
    $elem = $this->xpathSingle("//ac:cookie[@name='$name']");
    if (empty($elem)) {
      return FALSE;
    }

    return $this->cookieFromElem($elem);
  }

  /**
   * Get all cookies that should be set.
   *
   * @return \Symfony\Component\HttpFoundation\Cookie[]
   *   An array of cookie object.
   */
  public function getAllCookies() {
    $cookies = [];
    foreach ($this->xpath("//ac:cookie") as $elem) {
      $cookies[$elem->getAttribute('name')] = $this->cookieFromElem($elem);
    }
    return $cookies;
  }

  /**
   * Get a cookie from a dom element.
   *
   * @param \DOMElement $elem
   *   A dom element to search.
   *
   * @return \Symfony\Component\HttpFoundation\Cookie
   *   An array of cookie object.
   */
  private function cookieFromElem(\DOMElement $elem) {
    $age_max = $elem->getAttribute('age-maximum');
    if ($age_max == '-1') {
      // Session cookie - expire when browser is closed.
      $expire = 0;
    }
    elseif ($age_max == '0') {
      // Unset and expire the cookie now.
      $expire = 1;
    }
    else {
      // Regular cookie.
      $expire = time() + $age_max;
    }

    return new Cookie($elem->getAttribute('name'), urldecode($elem->nodeValue), $expire, $elem->getAttribute('path'));
  }

}
