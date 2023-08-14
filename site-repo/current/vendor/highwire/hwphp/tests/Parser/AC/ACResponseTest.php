<?php

use HighWire\Parser\AC\Response;
use HighWire\Parser\AC\Error;
use HighWire\Parser\AC\Identity;
use HighWire\Parser\AC\Privilege;
use PHPUnit\Framework\TestCase;

class ACResponseTest extends TestCase {

  /**
   * @expectedException Exception
   */
  public function testErrorException() {
    $resp = new Response(file_get_contents(__DIR__  . '/ac2-response-generic.xml'));

    // Check Authentication
    $authn = $resp->getAuthentication();
    $this->assertNotEmpty($authn);

    // Test errors
    $errors = $authn->getAllErrors();
    $error = $errors[0];
    $error->triggerException();
  }

  public function testParseAC2Reponse1() {
    $resp = new Response(file_get_contents(__DIR__  . '/ac2-response-generic.xml'));

    // Check Authentication
    $authn = $resp->getAuthentication();
    $this->assertNotEmpty($authn);

    // Test errors
    $errors = $authn->getAllErrors();
    $error = $errors[0];
    $this->assertEquals(get_class($error), Error::class);
    $this->assertEquals(get_class($error->getException()), \Exception::class);


    // Check Identity
    $identities = $authn->getAllIdentities();
    $this->assertEquals(2, count($identities));
    $this->assertEquals('urn:ac.highwire.org:guest:identity', $identities['urn:ac.highwire.org:guest:identity']->getId());

    $identity = $authn->getIdentity('70c33862-26d4-404f-a9a1-a4cc2916016a');
    $this->assertEquals('70c33862-26d4-404f-a9a1-a4cc2916016a', $identity->getId());
    $this->assertEquals('individual', $identity->getType());
    $this->assertEquals('1911', $identity->getUserId());
    $this->assertEquals('dupjnl_subdev', $identity->getSubcode());
    $this->assertEquals('TOLGA1', $identity->getCustomerNumber());
    $this->assertEquals('olga Biasotti', $identity->getDisplayName());
    $this->assertEquals('obiasotti@highwire.org', $identity->getEmail());

    // Check Privilege
    $priv = $identity->getPrivilege('5e47762d-1532-4b8d-b612-8a5166a8b51c');
    $this->assertEquals('5e47762d-1532-4b8d-b612-8a5166a8b51c', $priv->getId());
    $this->assertEquals('subscription', $priv->getType());
    $this->assertEquals('1911', $priv->getUserId());
    $this->assertEquals('ACTIVE', $priv->getStatus());
    $this->assertEquals(true, $priv->isActive());
    $this->assertEquals('9999-12-31T23:59:59.999-08:00', $priv->getExpiration());
    $this->assertEquals('MALL', $priv->getPrivilegeSet());

    $privs = $identity->getAllPrivileges();
    $this->assertInternalType('array', $privs);
    $this->assertEquals(Privilege::class, get_class($privs['f831968b-b799-4188-b449-7e7aa2492a41']));

    // Check login
    $this->assertEquals(true, $authn->isLoginOK());
    $this->assertEquals('70c33862-26d4-404f-a9a1-a4cc2916016a', $authn->getLoginIdentity()->getId());

    // Check message
    $this->assertEquals(1, count($authn->getAllMessages()));

    $message = $authn->getMessage('logged-in', 'username-password');
    $this->assertNotEmpty($message);
    $this->assertEquals('logged-in', $message->getName());
    $this->assertEquals('username-password', $message->getModule());
    $this->assertEquals(false, $message->isError());
    $this->assertEquals('', $message->getText());

    // Authorizations
    $this->assertEquals(1, count($resp->getAllAuthorizations()));

    $authz = $resp->getAuthorization('5e47762d-1532-4b8d-b612-8a5166a8b51a');
    $this->assertNotEmpty($authz);
    $this->assertEquals('5e47762d-1532-4b8d-b612-8a5166a8b51a', $authz->getId());
    $this->assertEquals('resource', $authz->getTarget());
    $this->assertEquals('/ddssh/34/4/523.atom', $authz->getUri());
    $this->assertEquals('full', $authz->getView());
    $this->assertEquals(TRUE, $authz->isAuthorized());

    $authz_ident = $authz->getAuthorizedIdentity();
    $this->assertNotEmpty($authz_ident);
    $this->assertEquals('70c33862-26d4-404f-a9a1-a4cc2916016a', $authz_ident->getId());

    $authz_priv = $authz->getAuthorizedPrivilege();
    $this->assertNotEmpty($authz_priv);
    $this->assertEquals('5e47762d-1532-4b8d-b612-8a5166a8b51c', $authz_priv->getId());

    $authorized = $authz->getAuthorized();
    $this->assertEquals(1, count($authorized));
    $this->assertEquals(NULL, $authorized[0]->getReason());
    $this->assertEquals('subscription', $authorized[0]->inferReason());

    $resource_authz = $resp->getResourceAuthorization('/ddssh/34/4/523.atom', 'full');
    $this->assertNotEmpty($resource_authz);
    $this->assertEquals('5e47762d-1532-4b8d-b612-8a5166a8b51a', $resource_authz->getId());
    $this->assertEquals('resource', $resource_authz->getTarget());
    $this->assertEquals('/ddssh/34/4/523.atom', $resource_authz->getUri());
    $this->assertEquals('full', $resource_authz->getView());
    $this->assertEquals(true, $resource_authz->isAuthorized());

    // HTTP Response
    $http = $resp->getHTTPResponse();
    $this->assertNotEmpty($http);
    $this->assertEquals(1, count($http->getAllCookies()));
    $this->assertEquals('login', $http->getAllCookies()['login']->getName());

    $login_cookie = $http->getCookie('login');
    $this->assertEquals('login', $login_cookie->getName());
    $this->assertEquals('/', $login_cookie->getPath());
    $this->assertEquals(0, $login_cookie->getExpiresTime());
    $this->assertEquals(0, $login_cookie->isCleared());
    $this->assertEquals('authn:1379965592:{AES}vndbOywqhL7FOAMAHu/rnwLlD5ojDXbjRL81odsT4qvOm84iyn2PziNw+4nTXDDHPxkP/1tYjEJ5k5zdvPShLw==:CqOeaQRcW+bL0khietufHw==', $login_cookie->getValue());

  }

  public function testParseAC3SigmaReponse1() {
    $resp = new Response(file_get_contents(__DIR__  . '/ac3-response-sigma.xml'));

    // Check Authentication.
    $authn = $resp->getAuthentication();
    $this->assertNotEmpty($authn);

    // Check Identity.
    $identities = $authn->getAllIdentities();
    $this->assertEquals(3, count($identities));
    $this->assertEquals('urn:ac.highwire.org:guest:identity', $identities['urn:ac.highwire.org:guest:identity']->getId());

    $identity = $authn->getIdentity('fb875dc4');
    $this->assertEquals('fb875dc4', $identity->getId());
    $this->assertEquals('individual', $identity->getType());
    $this->assertEquals('12', $identity->getUserId());
    $this->assertEquals('2', $identity->getSubcode());
    $this->assertEquals('Sunil Mehta (Individual)', $identity->getDisplayName());
    $this->assertEquals('mehtan@gmail.com', $identity->getEmail());

    // Check Privilege.
    $priv = $identity->getPrivilege('11598790');
    $this->assertNotEmpty($priv);
    $this->assertEquals('11598790', $priv->getId());
    $this->assertEquals('subscription', $priv->getType());
    $this->assertEquals('active', $priv->getStatus());
    $this->assertTrue($priv->isActive());
    $this->assertEquals('specific-resource', $priv->getPrivilegeSet());

    // Authorizations.
    $authzs = $resp->getAllAuthorizations();
    $this->assertEquals(2, count($authzs));

    $this->assertNotEmpty($authzs[0]);
    $this->assertEquals('resource', $authzs[0]->getTarget());
    $this->assertEquals('/freebird2/book/978-0-8261-1749-6/part/part01/chapter/3-8.atom', $authzs[0]->getUri());
    $this->assertTrue($authzs[0]->isAuthorized());
    $this->assertEquals('download', $authzs[0]->getScope());

    $authz_priv = $authzs[0]->getAuthorizedPrivilege();
    $this->assertNotEmpty($authz_priv);
    $this->assertEquals('43ec28af', $authz_priv->getId());

    // Test authorization and scope.
    $resource_authzs = $resp->getResourceAuthorizations('/freebird2/book/978-0-8261-1749-6/part/part01/chapter/3-8.atom');
    $this->assertNotEmpty($resource_authzs['download']);
    $download_auth = $resource_authzs['download'];
    $this->assertNotEmpty($download_auth);
    $this->assertEquals(get_class($download_auth), 'HighWire\Parser\AC\Authorization');
    $this->assertEquals('resource', $download_auth->getTarget());
    $this->assertEquals('/freebird2/book/978-0-8261-1749-6/part/part01/chapter/3-8.atom', $download_auth->getUri());
    $this->assertTrue($download_auth->isAuthorized());
    $this->assertEquals($download_auth->getScope(), 'download');

    $scopes = $download_auth->getAuthorizedScoped();
    $this->assertNotEmpty($scopes);

    $pdf_scope = $scopes[0];
    $this->assertEquals('variant', $pdf_scope->getTarget());
    $this->assertEquals('full-text', $pdf_scope->getRole());
    $this->assertEquals('application/pdf', $pdf_scope->getType());
    $this->assertEquals('en', $pdf_scope->getLanguage());

    $source_scope = $scopes[1];
    $this->assertEquals('variant', $source_scope->getTarget());
    $this->assertEquals('source', $source_scope->getRole());
    $this->assertEquals('application/xml', $source_scope->getType());
    $this->assertEquals('en', $source_scope->getLanguage());

    // Test authorization and scope.
    $this->assertNotEmpty($resource_authzs['online']);
    $online_auth = $resource_authzs['online'];
    $this->assertNotEmpty($online_auth);
    $this->assertEquals(get_class($online_auth), 'HighWire\Parser\AC\Authorization');
    $this->assertEquals('resource', $online_auth->getTarget());
    $this->assertEquals('/freebird2/book/978-0-8261-1749-6/part/part01/chapter/3-8.atom', $online_auth->getUri());
    $this->assertTrue($online_auth->isAuthorized());
    $this->assertEquals($online_auth->getScope(), 'online');

    $scopes = $online_auth->getAuthorizedScoped();
    $this->assertNotEmpty($scopes);

    $abstract_scope = $scopes[0];
    $this->assertEquals('variant', $abstract_scope->getTarget());
    $this->assertEquals('abstract', $abstract_scope->getRole());
    $this->assertEquals('application/xhtml+xml', $abstract_scope->getType());
    $this->assertEquals('en', $abstract_scope->getLanguage());

    $full_text_scope = $scopes[1];
    $this->assertEquals('variant', $full_text_scope->getTarget());
    $this->assertEquals('full-text', $full_text_scope->getRole());
    $this->assertEquals('application/xhtml+xml', $full_text_scope->getType());
    $this->assertEquals('en', $full_text_scope->getLanguage());

    $pdf_scoped = $scopes[2];
    $this->assertEquals('variant', $pdf_scoped->getTarget());
    $this->assertEquals('full-text', $pdf_scoped->getRole());
    $this->assertEquals('application/pdf', $pdf_scoped->getType());
    $this->assertEquals('en', $pdf_scoped->getLanguage());

    // Test user access

    // User has access to full-text variant, this could mean they
    // have access to full-text html and full-text pdf.
    $this->assertTrue($resp->userHasAccess('/freebird2/book/978-0-8261-1749-6/part/part01/chapter/3-8.atom', 'full-text'));

    // Test pdf access.
    $this->assertTrue($resp->userHasAccess('/freebird2/book/978-0-8261-1749-6/part/part01/chapter/3-8.atom', 'full-text', 'application/pdf'));

    // Test full-text html in the online scope.
    $this->assertTrue($resp->userHasAccess('/freebird2/book/978-0-8261-1749-6/part/part01/chapter/3-8.atom', 'full-text', 'application/xhtml+xml', 'online'));

    // Acccess denied to full-text html in download scope.
    $this->assertFalse($resp->userHasAccess('/freebird2/book/978-0-8261-1749-6/part/part01/chapter/3-8.atom', 'full-text', 'application/xhtml+xml', 'download'));

    // User has access to full-text pdf
    // in download scope with language of english.
    $this->assertTrue($resp->userHasAccess('/freebird2/book/978-0-8261-1749-6/part/part01/chapter/3-8.atom', 'full-text', 'application/pdf', 'download', 'en'));

    // Access denied to full-text pdf
    // in download scope with language of french.
    $this->assertFalse($resp->userHasAccess('/freebird2/book/978-0-8261-1749-6/part/part01/chapter/3-8.atom', 'full-text', 'application/pdf', 'download', 'fr'));

    // Access denied to full-text pdf
    // in download scope with language
    // of english and target of view.
    $this->assertFalse($resp->userHasAccess('/freebird2/book/978-0-8261-1749-6/part/part01/chapter/3-8.atom', 'full-text', 'application/pdf', 'download', 'fr', 'view'));

  }

  public function testIdentity() {
    $resp = new Response(file_get_contents(__DIR__ . '/ac2-response-generic-no-creds.xml'));
    $auth = $resp->getAuthentication();
    $idents = $auth->getAllIdentities();
    foreach ($idents as $ident) {
      $this->assertNull($ident->getCredentials());
      $this->assertNull($ident->getPrivilege('some id'));

    }
  }

  public function testInstructorAccess() {
    $resp = new Response(file_get_contents(__DIR__ . '/ac3-instructor-access.xml'));
    $resource_authorizations = $resp->getResourceAuthorizations('/sgrworks/book/978-0-8261-3184-3.atom');
    $instruct_auth = $resource_authorizations['instruct'];
    $this->assertNotEmpty($instruct_auth);
    $this->assertEquals(get_class($instruct_auth), 'HighWire\Parser\AC\Authorization');
    $this->assertEquals('resource', $instruct_auth->getTarget());
    $this->assertEquals('/sgrworks/book/978-0-8261-3184-3.atom', $instruct_auth->getUri());
    // User does _not_ have instructor access
    $this->assertFalse($instruct_auth->isAuthorized());
    $this->assertEquals($instruct_auth->getScope(), 'instruct');

  }

}
