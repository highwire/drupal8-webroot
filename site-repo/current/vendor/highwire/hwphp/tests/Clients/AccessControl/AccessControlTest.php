<?php

use HighWire\Clients\AccessControl\AccessControl;
use HighWire\Parser\AC\Request;
use HighWire\Parser\AC\Authorize;
use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as PSR7Response;
use HighWire\Parser\AC\AuthenticateRequest;
use PHPUnit\Framework\TestCase;

class AccessControlTest extends TestCase {
  protected $defaultClientParams = ['context' => 'freebird', 'publisherId' => 'springer'];

  public function testAuthentication() {

    // Build Request
    $request = new Request(); // New AC Request
    $request->setAuthenticateRequest(new AuthenticateRequest(file_get_contents(__DIR__ . '/ac2-authentication-request.xml')));
    $request->getAuthenticateRequest()->setClientHost('171.66.124.6'); // Set IP address for authn

    // Create a mock response guzzle handler
    $mock = new MockHandler([
        new PSR7Response(200, ['Content-Type' => 'application/xml'], file_get_contents(__DIR__  . '/authentication_by_ip.response.xml')),
        new PSR7Response(200, ['Content-Type' => 'application/xml'], file_get_contents(__DIR__  . '/authentication_by_ip.response.xml'))
    ]);
    $handler = HandlerStack::create($mock);

    // Build Client
    $client = ClientFactory::get('access-control', ['guzzle-config' => ['handler'  => $handler], 'client-config' => $this->defaultClientParams]);

    // Get Response
    $hw_response = $client->accessControl($request);

    // A response is a PSR-7 compliant response
    $this->assertEquals('200', $hw_response->getStatusCode());
    $this->assertEquals('OK', $hw_response->getReasonPhrase());
    $this->assertNotEmpty($hw_response->getHeaders());
    $this->assertEquals(['application/xml'], $hw_response->getHeader('Content-Type'));

    // Test Response
    $identities = $hw_response->getData()->getAuthentication()->getAllIdentities();
    $this->assertEquals(2, count($identities));
    foreach ($identities as $id => $identity) {
      if ($identity->isGuest()) {
        $this->assertEquals('urn:ac.highwire.org:guest:identity', $identity->getId());
      }
      else {
        // Stanford University
        $this->assertEquals('institution', $identity->getType());
        $this->assertEquals('689', $identity->getUserId());
        $this->assertEquals('gsw_subdev', $identity->getSubCode());
        $this->assertEquals('CU00144', $identity->getCustomerNumber());
        $this->assertEquals('Stanford University TEST', $identity->getDisplayName());
        $this->assertEquals('lindat@stanford.edu', $identity->getEmail());

        $credential = $identity->getCredentials();

        $this->assertEquals('ip', $credential->getMethod());
        $this->assertEquals('171.66.124.6', $credential->getValue());
      }
    }

    // Test Async
    $resp_promise = $client->accessControlAsync($request);
    // .. Do a bunch of other stuff until you need the AC Response..
    $resp = $resp_promise->wait();
    $this->assertNotEmpty(strval($resp->getData()));
  }

  public function testLogin() {

    $request = new Request();
    $request->setAuthenticateRequest(new AuthenticateRequest(file_get_contents(__DIR__ . '/ac2-authentication-request.xml')));
    $request->getAuthenticateRequest()->setLoginParameters('tolga1', 'highwire');

    // Create a mock response guzzle handler
    $mock = new MockHandler([
        new PSR7Response(200, ['Content-Type' => 'application/xml'], file_get_contents(__DIR__  . '/authentication_by_user_pass.response.xml')),
    ]);
    $handler = HandlerStack::create($mock);

    // Build Client
    $client = ClientFactory::get('access-control', ['guzzle-config' => ['handler'  => $handler], 'client-config' => $this->defaultClientParams]);

    $resp = $client->accessControl($request);

    $ok = $resp->getData()->getAuthentication()->isLoginOK();
    $this->assertTrue($ok);

    $identity = $resp->getData()->getAuthentication()->getLoginIdentity();
    $this->assertNotEmpty($identity);

    $this->assertEquals('individual', $identity->getType());
    $this->assertEquals('1103', $identity->getUserId());
    $this->assertEquals('gsw_subdev', $identity->getSubCode());
    $this->assertEquals('TOLGA1', $identity->getCustomerNumber());
    $this->assertEquals('Olga Biasotti', $identity->getDisplayName());
    $this->assertEquals('obiasottis@highwire.stanford.edu', $identity->getEmail());
    $this->assertFalse($identity->isGuest());

    $credential = $identity->getCredentials();

    $this->assertEquals('username', $credential->getMethod());
    $this->assertEquals('tolga1', $credential->getValue());

    $cookies = $resp->getData()->getHTTPResponse()->getAllCookies();
    $this->assertEquals(1, count($cookies));
    $this->assertNotEmpty($cookies['login']);

    $login_cookie = $resp->getData()->getHTTPResponse()->getCookie('login');
    $this->assertEquals('login', $login_cookie->getName());
    $this->assertEquals('/', $login_cookie->getPath());
    $this->assertStringStartsWith('authn:', $login_cookie->getValue());

  }

  public function tesBadLogin() {
    // Test Bad Login.

    // Create a mock response guzzle handler
    $mock = new MockHandler([
        new PSR7Response(200, ['Content-Type' => 'application/xml'], file_get_contents(__DIR__  . '/authentication_by_bad_user_pass.response.xml'))
    ]);

    $handler = HandlerStack::create($mock);

    // Build Client
    $client = ClientFactory::get('access-control', ['guzzle-config' => ['handler'  => $handler], 'client-config' => $this->defaultClientParams]);

    $request = new Request();
    $request->setAuthenticateRequest(new AuthenticateRequest(file_get_contents(__DIR__ . '/ac2-authentication-request.xml')));

    $request->getAuthenticateRequest()->setLoginParameters('tolga1', 'badpass');
    $resp = $client->accessControl($request); // Re-use same AC client
    $this->assertFalse($resp->getData()->getAuthentication()->isLoginOK());

    $error = $resp->getData()->getAuthentication()->getError('login-error');
    $this->assertNotEmpty($error);
    $this->assertTrue($error->isError());
    $this->assertEquals('Invalid password for username: tolga1', $error->getText());

    try {
      $error->triggerException();
    }
    catch (\Exception $e) {
      $this->assertEquals($e->getMessage(), 'AC Error: login-error - username-password Invalid password for username: tolga1');
    }
  }

  public function testAuthorize() {
    $request = new Request();
    $request->createResourceAuthorize("/jbc/292/17/7105.atom");
    $request->createResourceAuthorize("/jbc/early/2017/05/02/jbc.M116.753772.atom");

    // Create a mock response guzzle handler
    $mock = new MockHandler([
        new PSR7Response(200, ['Content-Type' => 'application/xml'], file_get_contents(__DIR__  . '/authorization.response.xml')),
        new PSR7Response(200, ['Content-Type' => 'application/xml'], file_get_contents(__DIR__  . '/authorization_by_view.response.xml'))
    ]);

    $handler = HandlerStack::create($mock);

    // Build Client
    $client = ClientFactory::get('access-control', ['guzzle-config' => ['handler'  => $handler], 'client-config' => $this->defaultClientParams]);

    $resp = $client->accessControl($request);

    $this->assertEquals(5, count($resp->getData()->getAllAuthorizations()));

    $this->assertTrue($resp->getData()->getResourceAuthorization('/jbc/292/17/7105.atom', 'abstract')->isAuthorized());
    $this->assertFalse($resp->getData()->getResourceAuthorization('/jbc/292/17/7105.atom', 'reprint')->isAuthorized());

    $authzs = $resp->getData()->getResourceAuthorizations('/jbc/292/17/7105.atom');
    $this->assertTrue($authzs['abstract']->isAuthorized());
    $this->assertFalse($authzs['reprint']->isAuthorized());

    // Get Identity that granted this authorization
    $identity = $authzs['abstract']->getAuthorizedIdentity();
    $this->assertTrue($identity->isGuest());

    // Get priviledge that granted this authorization
    $priv = $authzs['abstract']->getAuthorizedPrivilege();
    $this->assertTrue($priv->isGuest());
    $this->assertTrue($priv->isActive());

    // Alternative method of fetching authorizations explicitly asking on a view
    $request = new Request();

    $authz_request_1 = new Authorize();
    // Normally this is generated per request, but it needs to be static to properly unit test
    $authz_request_1->setId('4d8ed242-4171-11e7-8802-9ea4ce737c22');
    $authz_request_1->setResourceTarget('/jbc/292/17/7105.atom', 'full'); // optionally specify view
    $request->addACElement($authz_request_1);

    $authz_request_2 = new Authorize();
    $authz_request_2->setId('4d8eff6a-4171-11e7-8f74-0b65d18efbc8');
    $authz_request_2->setResourceTarget('/jbc/early/2017/05/02/jbc.M116.753772.atom'); // Don't specify a view to get all views
    $request->addACElement($authz_request_2);

    $resp = $client->accessControl($request);

    // We know that authz_request_1 will only ever return a single Authorization because we specified a view
    $authz_1 = $authz_request_1->getSingleAuthorization($resp->getData());
    $this->assertFalse($authz_1->isAuthorized());
    $this->assertEquals('access-denied', $authz_1->unauthorizedReason());
    $this->assertEquals('full', $authz_1->getView());
    $this->assertEquals('/jbc/292/17/7105.atom', $authz_1->getUri());

    // authz_request_2 might return one or more authorizations because we didn't specify a view
    $authz_2 = $authz_request_2->getAuthorizations($resp->getData());
    $this->assertEquals(2, count($authz_2));

    foreach ($authz_2 as $authorization) {
      $this->assertTrue($authorization->isAuthorized());
    }
  }
}
