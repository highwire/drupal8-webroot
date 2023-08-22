<?php

use HighWire\Parser\AC\Request;
use HighWire\Parser\AC\AuthenticateRequest;
use HighWire\Parser\AC\Authorize;
use HighWire\Parser\AC\Authorization;
use HighWire\Parser\AC\AuthorizeRequest;
use HighWire\Parser\AC\Identifier;
use PHPUnit\Framework\TestCase;

class ACRequestTest extends TestCase {

  public function testAC20ParseGeneric() {
    $request = new Request(file_get_contents(__DIR__  . '/ac2-request-generic.xml'));
    $authn = $request->getAuthenticateRequest();
    $this->assertEquals('171.66.124.6', $authn->getClientHost());
    $this->assertEquals('/', $authn->getPath());
    $this->assertEquals('http', $authn->getProtocol());
    $this->assertEquals('generic.highwire.org', $authn->getServerHost());
    $this->assertEquals('80', $authn->getServerPort());
    $this->assertEquals('GET', $authn->getMethod());
    $this->assertEquals('http://generic.highwire.org/current.dtl', $authn->getHeader('referer'));
    $this->assertNull($authn->getHeader('missing-header'));
    $this->assertEquals('true', $authn->getCookie('acceptsCookies'));
    $this->assertEquals('tolga1', $authn->getParameter('username'));
    $this->assertEquals('highwire', $authn->getParameter('code'));
    $headers = $authn->getAllHeaders();
    $this->assertInternalType('array', $headers);
    $this->assertNull($authn->getCookie('missing-cookie'));
    $cookies = $authn->getAllCookies();
    $this->assertInternalType('array', $cookies);
    $this->assertNull($authn->getParameter('missing-param'));
    $authn->setAllParameters(['params1' => 'value1', 'param2' => 'value2']);
    $params = $authn->getAllParameters();
    $this->assertInternalType('array', $params);

    $authz = $request->getAuthorize('496ab4df-ab3d-3ce5-2547-05824ae8f39c');
    $this->assertEquals('496ab4df-ab3d-3ce5-2547-05824ae8f39c', $authz->getId());
    $this->assertEquals('resource', $authz->getTarget());
    $this->assertEquals('/ddssh/34/4/523.atom', $authz->getUri());
    $this->assertEquals('full', $authz->getView());

    $authzs = $request->getAllAuthorizeRequests();
    $this->assertEquals(1, count($authzs));
    $this->assertEquals($authzs['496ab4df-ab3d-3ce5-2547-05824ae8f39c']->out(), $authz->out());


  }

  public function testAC20GenerateGeneric() {
    $request = new Request();

    $authn = new AuthenticateRequest();
    $authn->setClientHost('171.66.124.6');
    $authn->setPath('/');
    $authn->setProtocol('http');
    $authn->setServerHost('generic.highwire.org');
    $authn->setServerPort('80');
    $authn->setMethod('GET');
    $authn->setAllHeaders(['referer' => 'http://generic.highwire.org/current.dtl']);
    $authn->setAllCookies(['acceptsCookies' => 'true']);
    $authn->setLoginParameters('tolga1', 'highwire');

    $authz = new Authorize();
    $authz->setId('496ab4df-ab3d-3ce5-2547-05824ae8f39c');
    $authz->setResourceTarget('/ddssh/34/4/523.atom', 'full');

    $request->setAuthenticateRequest($authn);
    $request->addACElement($authz);

    $generic = new Request(file_get_contents(__DIR__  . '/ac2-request-generic.xml'));

    $request->formatOutput = true;
    $generic->formatOutput = true;

    $this->assertEquals($generic->out(), $request->out());
  }

  public function testAutofill() {
    $request = new Request(file_get_contents(__DIR__  . '/ac2-request-generic.xml'));
    $global_req = Symfony\Component\HttpFoundation\Request::createFromGlobals();
    $request->getAuthenticateRequest()->fillFromRequest($global_req);
    $this->addToAssertionCount(1);
  }

  public function testAC30SigmaRequest() {
    $request = new Request();
    $request->authorizeApath('/some/apath.atom');
    $request->authorizeApaths(['/ddssh/34/4/523.atom']);
    $auth_requests = $request->getAllAuthorizeRequests();

    foreach ($auth_requests as $uri => $auth_request) {
      $this->assertEquals(get_class($auth_request), AuthenticateRequest::class);
      $this->assertEquals('resource', $this->getTarget());
      $this->assertEquals('*', $this->getScope());
      $this->assertEquals($uri, $auth_request->getUri());
      $authz = $this->getAuthorizations();
      $this->assertEquals(get_class($auth_request), Authorization::class);
    }

    $request->addAuthorizingEntitlements('application/vnd.hw.sigma+json', file_get_contents(__DIR__ . '/sigma-user-payload.json'));
    $auth_ents = $request->getAuthorizingEntitlements();
    $this->assertNotEmpty($auth_ents);
    $sigma_ent = $request->getAuthorizingEntitlements('application/vnd.hw.sigma+json');
    $this->assertNotEmpty($sigma_ent);
    $this->assertEquals('application/vnd.hw.sigma+json', $sigma_ent[0]->getType());
  }

  public function testAuthorizeRequest() {
    $auth_request = new AuthorizeRequest();
    $auth_request->setUri('/some/apath.atom');
    $auth_request->setTarget('some_target');
    $auth_request->setScope('*');
    $this->assertEquals('*', $auth_request->getScope());
    $this->assertEquals('some_target', $auth_request->getTarget());
    $this->assertEquals('/some/apath.atom', $auth_request->getUri());
  }

  public function testAdminRequest() {
    $request = new Request();
    $request->setAdminRequest(TRUE);

    // When making an admin request, authenticate requests
    // and authorizing entitlements should be removed.
    $authn = new AuthenticateRequest();
    $authn->setClientHost('171.66.124.6');
    $authn->setPath('/');
    $authn->setProtocol('http');
    $authn->setServerHost('generic.highwire.org');
    $authn->setServerPort('80');
    $authn->setMethod('GET');
    $authn->setAllHeaders(['referer' => 'http://generic.highwire.org/current.dtl']);
    $authn->setAllCookies(['acceptsCookies' => 'true']);
    $authn->setLoginParameters('tolga1', 'highwire');
    $request->setAuthenticateRequest($authn);
    $request->addAuthorizingEntitlements('application/vnd.hw.sigma+json', file_get_contents(__DIR__  . '/sigma-user-payload.json'));
    $request->formatOutput = TRUE;
    $this->assertEquals(file_get_contents(__DIR__  . '/admin-user-request.xml'), $request->out());
  }

  public function testIdentifier() {
    $identifier = new Identifier();
    $identifier->setAdmin("true");
    $identifier->setLogin('guest');
    $identifier->setType('some-type');
    $this->assertEquals('<ac:identifier xmlns:ac="http://schema.highwire.org/Access" admin="true" login="guest" type="some-type" xmlns:gen="http://schema.highwire.org/Site/Generator"></ac:identifier>', $identifier->out());
  }

  /**
   * @expectedException Exception
   */
  public function testInvalidProtocol() {
    $request = new AuthenticateRequest();
    $request->setProtocol('ftp');
  }
}
