<?php

use HighWire\Parser\Logging\Entry;
use HighWire\Parser\AC\Response;
use PHPUnit\Framework\TestCase;

class LogEntryTest extends TestCase {

  public function testLogEntryBook() {
    $entry = new Entry(file_get_contents(__DIR__  . '/log-entry-book.xml'));
    $this->assertEquals('org.highwire.pisa.log.[service].[access]', $entry->getLog());
    $this->assertEquals('WfNLFpJoKgFb8g9b1h2NlQAAAAc', $entry->getRequestId());
    $this->assertEquals('2joFuK13FWxvgtvjRQ-KZzq8M6SSHswmbcxypmHFw5Q', $entry->getSessionId());
    $this->assertEquals('104.232.16.4', $entry->getClientHost());
    $this->assertEquals('connect.springerpubs.org', $entry->getServerHost());
    $this->assertEquals('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36', $entry->getUserAgent());
    $this->assertEquals('SCOLARIS3', $entry->getPlatform());
    $this->assertEquals('2017-10-27T15:04:55.038', $entry->getDatetime());
    $this->assertEquals('scolaris-sgrworks_production', $entry->getSitecode());
    $this->assertEquals('content', $entry->getService());

    $this->assertEquals('https://connect.springerpubs.org/search?query=autism', $entry->getReferrer());
    $this->assertEquals('/content/book/978-1-6170-5277-4', $entry->getUri());
    $this->assertEquals('/sgrworks/book/978-1-6170-5277-4.atom', $entry->getResourceUri());
    $this->assertEquals('sgrworks', $entry->getContext());

    $this->assertEquals('book', $entry->getUnitServed());
    $this->assertEquals('sigma:11111', $entry->getLicense());
    $this->assertEquals('full-text', $entry->getAcRuleId());
  }

  public function testLogEntryGenerateBook() {
    $entry = new Entry();
    $entry->setLog('org.highwire.pisa.log.[service].[access]');
    $entry->setRequestId('WfNLFpJoKgFb8g9b1h2NlQAAAAc');
    $entry->setSessionId('2joFuK13FWxvgtvjRQ-KZzq8M6SSHswmbcxypmHFw5Q');
    $entry->setClientHost('104.232.16.4');
    $entry->setServerHost('connect.springerpubs.org');
    $entry->setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36');
    $entry->setPlatform('SCOLARIS3');
    $entry->setDatetime('2017-10-27T15:04:55.038');
    $entry->setSitecode('scolaris-sgrworks_production');
    $entry->setService('content');
    $entry->setLogFeature('');
    $entry->setReferrer('https://connect.springerpubs.org/search?query=autism');
    $entry->setUri('/content/book/978-1-6170-5277-4');
    $entry->setResourceUri('/sgrworks/book/978-1-6170-5277-4.atom');
    $entry->setContext('sgrworks');
    $entry->setCollection('');
    $entry->setUnitServed('book');
    $entry->setLicense('sigma:11111');
    $entry->setAcRuleId('full-text');

    $log = new Entry(file_get_contents(__DIR__  . '/log-entry-book.xml'));

    $entry->formatOutput = true;
    $log->formatOutput = true;

    $this->assertEquals($log->out(), $entry->out());
  }

    public function testLogEntryBookChapter() {
    $entry = new Entry(file_get_contents(__DIR__  . '/log-entry-chapter.xml'));
    $this->assertEquals('org.highwire.pisa.log.[service].[access]', $entry->getLog());
    $this->assertTrue($entry->isAccessGranted());
    $this->assertEquals('WfNLFpJoKgFb8g9b1h2NlQAAAAc', $entry->getRequestId());
    $this->assertEquals('2joFuK13FWxvgtvjRQ-KZzq8M6SSHswmbcxypmHFw5Q', $entry->getSessionId());
    $this->assertEquals('104.232.16.4', $entry->getClientHost());
    $this->assertEquals('connect.springerpubs.org', $entry->getServerHost());
    $this->assertEquals('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36', $entry->getUserAgent());
    $this->assertEquals('SCOLARIS3', $entry->getPlatform());
    $this->assertEquals('2017-10-27T15:04:55.038', $entry->getDatetime());
    $this->assertEquals('scolaris-sgrworks_production', $entry->getSitecode());
    $this->assertEquals('content', $entry->getService());
    $this->assertEquals('en', $entry->getLang());
    $this->assertEquals('abstract', $entry->getRole());
    $this->assertEquals('variant', $entry->getTarget());
    $this->assertEquals('application/xhtml+xml', $entry->getType());

    $this->assertEquals('https://connect.springerpubs.org/content/book/978-1-6170-5277-4', $entry->getReferrer());
    $this->assertEquals('/content/book/978-1-6170-5277-4/chapter/ch03', $entry->getUri());
    $this->assertEquals('/sgrworks/book/978-1-6170-5277-4/chapter/ch03.atom', $entry->getResourceUri());
    $this->assertEquals('sgrworks', $entry->getContext());

    $this->assertEquals('chapter', $entry->getUnitServed());
    $this->assertEquals('sigma:11111', $entry->getLicense());
    $this->assertEquals('full-text', $entry->getAcRuleId());

  }

  public function testLogEntryGenerateBookChapter() {
    $entry = new Entry();
    $entry->logAccessGranted();
    $entry->setRequestId('WfNLFpJoKgFb8g9b1h2NlQAAAAc');
    $entry->setSessionId('2joFuK13FWxvgtvjRQ-KZzq8M6SSHswmbcxypmHFw5Q');
    $entry->setClientHost('104.232.16.4');
    $entry->setServerHost('connect.springerpubs.org');
    $entry->setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36');
    $entry->setPlatform('SCOLARIS3');
    $entry->setDatetime('2017-10-27T15:04:55.038');
    $entry->setSitecode('scolaris-sgrworks_production');
    $entry->setService('content');
    $entry->setLogFeature('');
    $entry->setReferrer('https://connect.springerpubs.org/content/book/978-1-6170-5277-4');
    $entry->setUri('/content/book/978-1-6170-5277-4/chapter/ch03');
    $entry->setResourceUri('/sgrworks/book/978-1-6170-5277-4/chapter/ch03.atom');
    $entry->setContext('sgrworks');
    $entry->setCollection('');
    $entry->setUnitServed('chapter');
    $entry->setLang('en');
    $entry->setRole('abstract');
    $entry->setTarget('variant');
    $entry->setType('application/xhtml+xml');
    $entry->setLicense('sigma:11111');
    $entry->setAcRuleId('full-text');

    $log = new Entry(file_get_contents(__DIR__  . '/log-entry-chapter.xml'));

    $entry->formatOutput = true;
    $log->formatOutput = true;

    $this->assertEquals($log->out(), $entry->out());
  }

  public function testLogEntrySearch() {
    $entry = new Entry(file_get_contents(__DIR__  . '/log-entry-search.xml'));
    $this->assertEquals('org.highwire.pisa.log.[service].[access]', $entry->getLog());
    $this->assertEquals('WfNLFpJoKgFb8g9b1h2NlQAAAAc', $entry->getRequestId());
    $this->assertEquals('2joFuK13FWxvgtvjRQ-KZzq8M6SSHswmbcxypmHFw5Q', $entry->getSessionId());
    $this->assertEquals('104.232.16.4', $entry->getClientHost());
    $this->assertEquals('connect.springerpubs.org', $entry->getServerHost());
    $this->assertEquals('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36', $entry->getUserAgent());
    $this->assertEquals('SCOLARIS3', $entry->getPlatform());
    $this->assertEquals('2017-10-27T15:04:55.038', $entry->getDatetime());
    $this->assertEquals('scolaris-sgrworks_production', $entry->getSitecode());
    $this->assertEquals('search', $entry->getService());
    $this->assertEquals('basic', $entry->getLogFeature());
    $this->assertEquals('https://connect.springerpubs.org/', $entry->getReferrer());
    $this->assertEquals('/search?query=leukemia+bad', $entry->getUri());
    $this->assertEquals('leukemia+bad', $entry->getSearchTerms());
    $this->assertEquals('sgrworks', $entry->getContext());

  }

  public function testLogEntryGenerateSearch() {
    $entry = new Entry();
    $entry->setLog('org.highwire.pisa.log.[service].[access]');
    $entry->setRequestId('WfNLFpJoKgFb8g9b1h2NlQAAAAc');
    $entry->setSessionId('2joFuK13FWxvgtvjRQ-KZzq8M6SSHswmbcxypmHFw5Q');
    $entry->setClientHost('104.232.16.4');
    $entry->setServerHost('connect.springerpubs.org');
    $entry->setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36');
    $entry->setPlatform('SCOLARIS3');
    $entry->setDatetime('2017-10-27T15:04:55.038');
    $entry->setSitecode('scolaris-sgrworks_production');
    $entry->setService('search');
    $entry->setLogFeature('basic');
    $entry->setReferrer('https://connect.springerpubs.org/');
    $entry->setUri('/search?query=leukemia+bad');
    $entry->setSearchTerms('leukemia+bad');
    $entry->setContext('sgrworks');

    $log = new Entry(file_get_contents(__DIR__  . '/log-entry-search.xml'));

    $entry->formatOutput = true;
    $log->formatOutput = true;

    $this->assertEquals($log->out(), $entry->out());
  }

  public function testLogEntryMultipleAuthorizedElements() {
    // Single authorization check against Full-text role.
    $ac_response = new Response(file_get_contents(__DIR__  . '/ac-response-multiple-authorization-elements.xml'));
    $entry = new Entry();
    $entry->fillFromACResponse($ac_response, ['role' => 'full-text']);

    $global_req = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    $entry->fillFromRequest($global_req);

    $authns = $entry->getAuthentications();
    $this->assertEquals(count($authns), 2);
    $authzs = $entry->getAuthorizations();
    $this->assertEquals(count($authzs), 1);

    $this->assertEquals('119', $authns[0]->profileId());
    $this->assertEquals('HighWire Press', $authns[0]->profileName());
    $this->assertEquals('institution', $authns[0]->profileType());
    $this->assertEquals('ip', $authns[0]->identifierType());

    // Multiple authorization check against Abstract role.
    $entry = new Entry();
    $entry->fillFromACResponse($ac_response, ['role' => 'abstract']);

    $global_req = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    $entry->fillFromRequest($global_req);

    $authns = $entry->getAuthentications();
    $this->assertEquals(count($authns), 2);
    $authzs = $entry->getAuthorizations();
    $this->assertEquals(count($authzs), 2);

    $this->assertEquals('119', $authns[0]->profileId());
    $this->assertEquals('HighWire Press', $authns[0]->profileName());
    $this->assertEquals('institution', $authns[0]->profileType());
    $this->assertEquals('ip', $authns[0]->identifierType());

    $this->assertEquals('140', $authns[1]->profileId());
    $this->assertEquals('Ravencroft Institute', $authns[1]->profileName());
    $this->assertEquals('institution', $authns[1]->profileType());
    $this->assertEquals('ip', $authns[1]->identifierType());
  }

  public function testLogEntryAutofill() {
    $ac_response = new Response(file_get_contents(__DIR__  . '/ac-response.xml'));
    $entry = new Entry();
    $entry->fillFromACResponse($ac_response);    

    $global_req = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    $entry->fillFromRequest($global_req);
    $entry->setLicense('sigma:11111');

    $this->assertEquals('sigma:11111', $entry->getLicense());

    $entry->setConcurrencyAccess(TRUE);
    $this->assertEquals(TRUE, $entry->getConcurrencyAccess());

    $entry->setConcurrencyAccess(FALSE);
    $this->assertEquals(FALSE, $entry->getConcurrencyAccess());

    $authentications = $entry->getAuthentications();
    $this->assertEquals(count($authentications), 3);
    $this->assertEquals('12', $authentications[0]->profileId());
    $this->assertEquals('Sunil Mehta (Individual)', $authentications[0]->profileName());
    $this->assertEquals('individual', $authentications[0]->profileType());
    $this->assertEquals('username', $authentications[0]->identifierType());

    $authorizations = $entry->getAuthorizations();

    $this->assertEquals(count($authorizations), 2);
    $this->assertEquals('6', $authorizations[0]->userId());
    $this->assertEquals('resource', $authorizations[0]->target());
    $this->assertTrue($authorizations[0]->authorized());
    $this->assertEquals('ip', $authorizations[0]->authnMethod());
    $this->assertEquals('sigma', $authorizations[0]->authnCredientials());
//    $this->assertEquals('[8] Genetics - Journal', $authorizations[0]->privilegeSet());
//    $this->assertEquals('active', $authorizations[0]->privilegeStatus());
//    $this->assertEquals('subscription', $authorizations[0]->privilegeType());
    $this->assertEmpty($authorizations[0]->privilegeResource());

    $ac_response = new Response(file_get_contents(__DIR__  . '/ac-response.xml'));
    $entry = new Entry();
    $entry->fillFromACResponse($ac_response);
    $authentications = $entry->getAuthentications();
    $this->assertEquals(3, count($authentications));
  }

  public function testAutoFillFromSigma() {
    $sigma_data = json_decode(file_get_contents(__DIR__  . '/sigma-license-data.authenticated_profiles.json'), TRUE);
    $entry = new Entry();
    $entry->fillFromSigmaAuthenticatedProfiles($sigma_data);

    $authentications = $entry->getAuthentications();
    $this->assertEquals(count($authentications), 3);

    $this->assertEquals('6', $authentications[0]->profileId());
    $this->assertEquals('UC Berkeley', $authentications[0]->profileName());
    $this->assertEquals('organization', $authentications[0]->profileType());
    $this->assertEquals('IP_RANGE', $authentications[0]->identifierType());

    $this->assertEquals('12', $authentications[1]->profileId());
    $this->assertEquals('Sunil Mehta (Individual)', $authentications[1]->profileName());
    $this->assertEquals('individual', $authentications[1]->profileType());
    $this->assertEquals('USER_PASS', $authentications[1]->identifierType());

    $this->assertEquals('2', $authentications[2]->profileId());
    $this->assertEquals('Springer Publications (PUBLISHER)', $authentications[2]->profileName());
    $this->assertEquals('publisher', $authentications[2]->profileType());
    $this->assertEquals('', $authentications[2]->identifierType());
  }

}
