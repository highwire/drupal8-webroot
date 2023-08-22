<?php

namespace HighWire\Parser\Logging;

use BetterDOMDocument\DOMDoc;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use HighWire\Parser\DOMElementBase;
use HighWire\Parser\AC\Response;

/**
 * Counter Logging Entries.
 *
 * Example XML:
 * @code
 * <log:entry xmlns:log="http://schema.highwire.org/Service/Log"
 *   request-id="WfIMc-VVOkOTwtEiP2Q5RAAAAAo"
 *   session-id="2joFuK13FWxvgtvjRQ-KZzq8M6SSHswmbcxypmHFw5Q"
 *   client-host="104.232.16.4"
 *   server-host="connect.springerpub.com"
 *   referrer="https://connect.springerpub.com/search"
 *   user-agent="Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36"
 *   platform="SCOLARIS3"
 *   sitecode="scolaris-sgrworks"
 *   context="sgrworks"
 *   uri="content/book/978-1-6170-5277-4"
 *   service="content"
 *   feature="home"
 *   resource-uri="/sgrworks/book/978-1-6170-5277-4.atom"
 *   unit-served="book"
 *   lang="en"
 *   role="abstract"
 *   target="variant"
 *   type="application/xhtml+xml"
 *   datetime="2017-10-26T16:25:23.637" >
 * </entry>
 * @endcode
 *
 * @see http://confluence.highwire.org/display/HWDP/XML+Service+Log+Specification
 */
class Entry extends DOMDoc {

  /**
   * An array of ac response authorization ids.
   * This is used to make sure we don't add the same
   * log:authorization more than once.
   *
   * @var array
   */
  protected $addedLogAuthzIds = [];

  /**
   * Create a LoggingRequest object.
   *
   * @param string $log_entry_xml
   *   Optionally pass an entire request object string.
   */
  public function __construct($log_entry_xml = '') {
    if (!empty($log_entry_xml)) {
      parent::__construct($log_entry_xml);
    }
    else {
      parent::__construct('<log:entry xmlns:log="http://schema.highwire.org/Service/Log"></log:entry>');
    }

    $this->registerNamespace('log', 'http://schema.highwire.org/Service/Log');
  }

  /**
   * Set the log access type.
   *
   * @param string $log
   *   The log access type.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setLog(string $log) {
    $this->documentElement->setAttribute('log', $log);
    return $this;
  }

  /**
   * Get the log access type.
   *
   * @return string
   *   The log access type.
   */
  public function getLog(): string {
    return $this->documentElement->getAttribute('log');
  }

  /**
   * Mark that access has been granted
   */
  public function logAccessGranted() {
    return $this->documentElement->setAttribute('log', 'org.highwire.pisa.log.[service].[access]');
  }

  /**
   * Mark that access has been denied
   */
  public function logAccessDenied() {
    return $this->documentElement->setAttribute('log', 'org.highwire.pisa.log.[service].[access-deny]');
  }

  /**
   * Check if access has been granted.
   *
   * @return bool|null
   *   Returns TRUE if access has been granted, FALSE if access has been denied, and NULL if unknown.
   */
  public function isAccessGranted() {
    if ($this->getLog() == 'org.highwire.pisa.log.[service].[access]') {
      return TRUE;
    }
    if ($this->getLog() == 'org.highwire.pisa.log.[service].[access-deny]') {
      return FALSE;
    }

    return NULL;
  }

  /**
   * Set the server request ID.
   *
   * @param string $id
   *   The unique request ID.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setRequestId(string $id) {
    $this->documentElement->setAttribute('request-id', $id);
    return $this;
  }

  /**
   * Get the server request ID.
   *
   * @return string
   *   The server request ID.
   */
  public function getRequestId(): string {
    return $this->documentElement->getAttribute('request-id');
  }

  /**
   * Set the session ID.
   *
   * @param string $id
   *   The unique session ID.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setSessionId(string $id) {
    $this->documentElement->setAttribute('session-id', $id);
    return $this;
  }

  /**
   * Get the session ID.
   *
   * @return string
   *   The session ID.
   */
  public function getSessionId(): string {
    return $this->documentElement->getAttribute('session-id');
  }

  /**
   * Set the request client IP.
   *
   * @param string $ip
   *   The request client IP.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setClientHost(string $ip) {
    $this->documentElement->setAttribute('client-host', $ip);
    return $this;
  }

  /**
   * Get the client IP.
   *
   * @return string
   *   The client IP.
   */
  public function getClientHost(): string {
    return $this->documentElement->getAttribute('client-host');
  }

  /**
   * Set the server host.
   *
   * @param string $host
   *   The server host.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setServerHost(string $host) {
    $this->documentElement->setAttribute('server-host', $host);
    return $this;
  }

  /**
   * Get the server host.
   *
   * @return string
   *   The server host.
   */
  public function getServerHost(): string {
    return $this->documentElement->getAttribute('server-host');
  }

  /**
   * Set the client user agent.
   *
   * @param string $ua
   *   The client user agent.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setUserAgent(string $ua) {
    $this->documentElement->setAttribute('user-agent', $ua);
    return $this;
  }

  /**
   * Get the client user agent.
   *
   * @return string
   *   The client user agent.
   */
  public function getUserAgent(): string {
    return $this->documentElement->getAttribute('user-agent');
  }

  /**
   * Set the platform
   *
   * @param string $platform
   *   The source platform.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setPlatform(string $platform) {
    $this->documentElement->setAttribute('platform', $platform);
    return $this;
  }

  /**
   * Get the source platform.
   *
   * @return string
   *   The source platform.
   */
  public function getPlatform(): string {
    return $this->documentElement->getAttribute('platform');
  }

  /**
   * Set the entry time record.
   *
   * @param string $time
   *   The entry time, formatted.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setDatetime(string $time) {
    $this->documentElement->setAttribute('datetime', $time);
    return $this;
  }

  /**
   * Get the entry time record.
   *
   * @return string
   *   The entry time record.
   */
  public function getDatetime(): string {
    return $this->documentElement->getAttribute('datetime');
  }

  /**
   * Set the sitecode.
   *
   * @param string $site
   *   The sitecode.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setSitecode(string $site) {
    $this->documentElement->setAttribute('sitecode', $site);
    return $this;
  }

  /**
   * Get the sitecode.
   *
   * @return string
   *   The sitecode.
   */
  public function getSitecode(): string {
    return $this->documentElement->getAttribute('sitecode');
  }

  /**
   * Set the entry service.
   *
   * @param string $service
   *   The service used for this entry.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setService(string $service) {
    $this->documentElement->setAttribute('service', $service);
    return $this;
  }

  /**
   * Get the entry service.
   *
   * @return string
   *   The service for this entry.
   */
  public function getService(): string {
    return $this->documentElement->getAttribute('service');
  }

  /**
   * Set the entry feature.
   *
   * @param string $feature
   *   The feature used for this entry.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setFeature(string $feature) {
    $this->documentElement->setAttribute('feature', $feature);
    return $this;
  }

  /**
   * Get the entry feature.
   *
   * Note that this is not named getFeature because it conflicts with DOMDocument.
   *
   * @return string
   *   The feature for this entry.
   */
  public function feature(): string {
    return $this->documentElement->getAttribute('feature');
  }

  /**
   * Set the entry feature.
   *
   * @param string $feature
   *   The feature used for this entry.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setLogFeature(string $feature) {
    $this->documentElement->setAttribute('feature', $feature);
    return $this;
  }

  /**
   * Get the entry feature.
   *
   * @return string
   *   The feature for this entry.
   */
  public function getLogFeature(): string {
    return $this->documentElement->getAttribute('feature');
  }

  /**
   * Set the http referrer.
   *
   * @param string $ref
   *   The http referrer.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setReferrer(string $ref) {
    $this->documentElement->setAttribute('referrer', $ref);
    return $this;
  }

  /**
   * Get the http referrer.
   *
   * @return string
   *   The http referrer.
   */
  public function getReferrer(): string {
    return $this->documentElement->getAttribute('referrer');
  }

  /**
   * Set the request uri.
   *
   * @param string $uri
   *   The requested uri.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setUri(string $uri) {
    $this->documentElement->setAttribute('uri', $uri);
    return $this;
  }

  /**
   * Get the request uri.
   *
   * @return string
   *   The requested uri.
   */
  public function getUri(): string {
    return $this->documentElement->getAttribute('uri');
  }

  /**
   * Set the resource uri (apath).
   *
   * @param string $apath
   *   The resource requested.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setResourceUri(string $apath) {
    $this->documentElement->setAttribute('resource-uri', $apath);
    return $this;
  }

  /**
   * Get the requested resource uri (apath).
   *
   * @return string
   *   The resource uri (apath).
   */
  public function getResourceUri(): string {
    return $this->documentElement->getAttribute('resource-uri');
  }

  /**
   * Set the entry context.
   *
   * @param string $corpus
   *   The corpus for this site.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setContext(string $corpus) {
    $this->documentElement->setAttribute('context', $corpus);
    return $this;
  }

  /**
   * Get the site's context.
   *
   * @return string
   *   The context for this entry.
   */
  public function getContext(): string {
    return $this->documentElement->getAttribute('context');
  }

  /**
   * Set the collection.
   *
   * Generally for journals.
   *
   * @param string $coll
   *   The collection id.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setCollection(string $coll) {
    $this->documentElement->setAttribute('collection', $coll);
    return $this;
  }

  /**
   * Get the collection id.
   *
   * @return string
   *   The collection id.
   */
  public function getCollection(): string {
    return $this->documentElement->getAttribute('collection');
  }

  /**
   * Set the book unit served.
   *
   * @param string $item
   *   The book unit served.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setUnitServed(string $item) {
    // Scrub item_ off the item type, might want to remove this in the future.
    $item = str_replace("item_", "", $item);
    $this->documentElement->setAttribute('unit-served', $item);
    return $this;
  }

  /**
   * Get the book unit served.
   *
   * @return string
   *   The book unit served.
   */
  public function getUnitServed(): string {
    return $this->documentElement->getAttribute('unit-served');
  }

  /**
   * Set the book unit location (page).
   *
   * @param string $loc
   *   The book page.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setLocation(string $loc) {
    $this->documentElement->setAttribute('location', $loc);
    return $this;
  }

  /**
   * Get the book page served.
   *
   * @return string
   *   The book location (page).
   */
  public function getLocation():string {
    return $this->documentElement->getAttribute('location');
  }

  /**
   * Set search terms supplied.
   *
   * @param string $terms
   *   The set of search terms, separated by '+'.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setSearchTerms(string $terms) {
    $this->documentElement->setAttribute('search-terms', $terms);
    return $this;
  }

  /**
   * Get the book page served.
   *
   * @return string
   *   The book location (page).
   */
  public function getSearchTerms(): string {
    return $this->documentElement->getAttribute('search-terms');
  }

  /**
   * Set the language served.
   *
   * @param string $language
   *   Language, for example 'en'.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setLang(string $language) {
    $this->documentElement->setAttribute('lang', $language);
    return $this;
  }

  /**
   * Get the language.
   *
   * @return string
   *   The language (eg 'en'), from the lang attribute.
   */
  public function getLang(): string {
    return $this->documentElement->getAttribute('lang');
  }

  /**
   * Set the role / scope.
   *
   * @param string $role
   *   The scope / role, eg 'abstract'.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setRole(string $role) {
    $this->documentElement->setAttribute('role', $role);
    return $this;
  }

  /**
   * Get the role.
   *
   * @return string
   *   The role (eg 'abstract'), from the role attribute.
   */
  public function getRole(): string {
    return $this->documentElement->getAttribute('role');
  }

  /**
   * Get the license.
   *
   * @return string
   *   The license, from the license attribute.
   */
  public function getLicense(): string {
    return $this->documentElement->getAttribute('license');
  }

  /**
   * Set the license.
   *
   * @param string $license
   *   The license providing access.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setLicense(string $license) {
    $this->documentElement->setAttribute('license', $license);
    return $this;
  }

  /**
   * Get the concurrency access.
   *
   * @return bool
   *   The concurrency access, from the concurrency-access attribute.
   */
  public function getConcurrencyAccess(): bool {
    return (boolean) $this->documentElement->getAttribute('concurrency-access');
  }

  /**
   * Set the concurrency access.
   *
   * @param bool $concurrency_access
   *   Whether concurrency access is granted or not.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setConcurrencyAccess(bool $concurrency_access) {
    $this->documentElement->setAttribute('concurrency-access', $concurrency_access);
    return $this;
  }

  /**
   * Get the AC Rule ID.
   *
   * @return string
   *   The AC Rule ID, from the ac-rule-id attribute.
   */
  public function getAcRuleId(): string {
    return $this->documentElement->getAttribute('ac-rule-id');
  }

  /**
   * Set the AC Rule ID.
   *
   * @param string $ac_rule_id
   *   The AC Rule ID that was checked.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setAcRuleId(string $ac_rule_id) {
    $this->documentElement->setAttribute('ac-rule-id', $ac_rule_id);
    return $this;
  }

  /**
   * Set the target.
   *
   * @param string $target
   *   The target, eg 'variant'.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setTarget(string $target) {
    $this->documentElement->setAttribute('target', $target);
    return $this;
  }

  /**
   * Get the target.
   *
   * @return string
   *   The role (eg 'variant'), from the target attribute.
   */
  public function getTarget(): string {
    return $this->documentElement->getAttribute('target');
  }

  /**
   * Set the content (mime) type.
   *
   * @param string $type
   *   The mime type, eg 'application/xhtml+xml'.
   *
   * @return self
   *   Return for method chaining.
   */
  public function setType(string $type) {
    $this->documentElement->setAttribute('type', $type);
    return $this;
  }

  /**
   * Get the content (mime) type.
   *
   * @return string
   *   The mime type (eg 'application/xhtml+xml'), from the type attribute.
   */
  public function getType(): string {
    return $this->documentElement->getAttribute('type');
  }

  /**
   * Fill in values from a symfony request object.
   *
   * This method will fill in the following attributes:
   *  - client-host
   *  - server-host
   *  - referrer
   *  - request-id
   *  - session-id
   *  - user-agent
   *  - datetime
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
   *   $log_entry = new LogEntry();
   *   $log_entry->fillFromRequest(Request::createFromGlobals());
   *
   *   # Using Drupal
   *   use Drupal;
   *   $log_entry = new LogEntry();
   *   $log_entry->fillFromRequest(Drupal::request());
   * @endcode
   */
  public function fillFromRequest(SymfonyRequest $request) {
    $is_ajax = 'XMLHttpRequest' == ( $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '' );
    $referrer = $request->headers->get('referer'); // Mispelling intentional

    $this->setServerHost($request->getHost());
    if ($client_ip = $request->getClientIp()) {
      $this->setClientHost($client_ip);
    }
    if ($request_id = $request->server->get('UNIQUE_ID')) {
      $this->setRequestId($request_id);
    }
    if ($request->hasSession()) {
      $this->setSessionId($request->getSession()->getId());
    }
    if ($user_agent = $request->server->get('HTTP_USER_AGENT')) {
      $this->setUserAgent($user_agent);
    }
    if ($referrer) {
      $this->setReferrer($referrer);
    }
    if ($is_ajax && $referrer) {
      $url_parts = parse_url($referrer);
      $uri = $url_parts['path'];
      if (!empty($url_parts['query'])) {
        $uri .= '?' . $url_parts['query'];
      }
      if (!empty($url_parts['fragment'])) {
        $uri .= '#' . $url_parts['fragment'];
      }
      $this->setUri($uri);
    }
    elseif ($uri = $request->getRequestUri()) {
      $this->setUri($uri);
    }

    // Set the time
    $time_parts = explode('.', microtime(TRUE));
    $time_seconds = $time_parts[0];
    $time_micro = substr($time_parts[1], 0, 3);
    $this->setDatetime(date('Y-m-d\TH:i:s', $time_seconds) . '.' . $time_micro);

    return $this;
  }

  /**
   * Fill values from an Access Control response
   *
   * @param \HighWire\Parser\AC\Response $response
   *   An access control response object.
   * @param array $args
   *   Additional arguments.
   *   bool 'authentication' will log authentication elements.
   *   string 'role' will filter Authorized elements by role.
   *   string 'license' adds license id that provided access.
   */
  public function fillFromACResponse(Response $response, array $args = []) {
    extract($args);
    // Attach all log:authentication elements.
    if ($authentication ?? TRUE) {
      if ($ac_authn = $response->getAuthentication()) {
        foreach ($ac_authn->getAllIdentities() as $ident) {
          // Don't log guests.
          if ($ident->isGuest()) {
            continue;
          }
          $log_authn = new Authentication();
          $log_authn->fillFromACIdentity($ident);
          $this->setAuthentication($log_authn);
        }
      }

      // Check for concurrency access and attach to log.
      if (isset($concurrency) && $concurrency !== NULL) {
        $this->setConcurrencyAccess($concurrency);
      }
    }

    // Attach all log:authorization elements.
    foreach ($response->getAllAuthorizations() as $ac_authz) {
      foreach ($ac_authz->getAuthorized($role ?? NULL) as $authorized) {
        // Skip duplicates
        $authid = $authorized->getIdentityId() . $authorized->getPrivilegeId();
        if (in_array($authid, $this->addedLogAuthzIds)) {
          continue;
        }

        $this->addedLogAuthzIds[] = $authid;
        $log_authz = new Authorization();
        $log_authz->fillFromACAuthorized($authorized);
        if ($log_authz->authorized()) {
          $this->setAuthorization($log_authz);
        }
      }
    }
  }

  /**
   * Fill values from a sigma license data
   *
   * @param array $authenticated_profiles
   *   An array with sigma authenticated profiles in a structured format.
   *   This array should come from the "authenticated_profiles" key in sigma license data.
   */
  public function fillFromSigmaAuthenticatedProfiles(array $authenticated_profiles) {

    // Organizations
    if (!empty($authenticated_profiles['organizationProfiles'])) {
      foreach ($authenticated_profiles['organizationProfiles'] as $profile) {
        $log_authn = new Authentication();
        $log_authn->setProfileType('organization');
        $log_authn->setProfileId($profile['profileId']);
        $log_authn->setIdentifierType($profile['identifierType']);
        $log_authn->setProfileName($profile['profileName']);
        $this->setAuthentication($log_authn);
      }
    }

    // Individual
    if (!empty($authenticated_profiles['individualProfile'])) {
      $profile = $authenticated_profiles['individualProfile'];
      $log_authn = new Authentication();
      $log_authn->setProfileType('individual');
      $log_authn->setProfileId($profile['profileId']);
      $log_authn->setIdentifierType($profile['identifierType']);
      $log_authn->setProfileName($profile['profileName']);
      $this->setAuthentication($log_authn);
    }

    // Publisher
    if (!empty($authenticated_profiles['publisherProfile'])) {
      $profile = $authenticated_profiles['publisherProfile'];
      $log_authn = new Authentication();
      $log_authn->setProfileType('publisher');
      $log_authn->setProfileId($profile['profileId']);
      $log_authn->setProfileName($profile['profileName']);
      $this->setAuthentication($log_authn);
    }

  }

  /**
   * Set a child <log:authentication> element.
   *
   * @param \HighWire\Parser\Logging\Authentication $authn
   *   A <log:authentication> object.
   *
   * @return self
   *   Return self for method chaining.
   *
   * @see getAuthentications()
   */
  public function setAuthentication(Authentication $authn) {
    // First check to make sure we are not adding a duplicate
    $profile_id = $authn->profileId();
    $profile_type = $authn->profileType();
    $id_type = $authn->identifierType();
    $existing = $this->xpathSingle(".//log:authentication[@profile-id='$profile_id'][@profile-type='$profile_type'][@identifier-type='$id_type']");
    if ($existing) {
      return $this;
    }

    $this->AddElement($authn);
    return $this;
  }

  /**
   * Get all <log:authentication> elements.
   *
   * @return \HighWire\Parser\Logging\Authentication[]
   *   An array of authentications.
   */
  public function getAuthentications(): array {
    $authns = [];
    foreach ($this->xpath('.//log:authentication') as $elem) {
      $authn = new Authentication($elem, $this);
      $authns[] = $authn;
    }
    return $authns;
  }

  /**
   * Set a child <log:authorization> element.
   *
   * @param \HighWire\Parser\Logging\Authorization $authz
   *   A <log:authorization> object.
   *
   * @return self
   *   Return self for method chaining.
   *
   * @see getAuthentications()
   */
  public function setAuthorization(Authorization $authz) {
    $this->AddElement($authz);
    return $this;
  }

  /**
   * Get all <log:authorization > elements.
   *
   * @return \HighWire\Parser\Logging\Authorization[]
   *   An array of authorizations.
   */
  public function getAuthorizations(): array {
    $authzs = [];
    foreach ($this->xpath('.//log:authorization') as $elem) {
      $authz = new Authorization($elem, $this);
      $authzs[] = $authz;
    }
    return $authzs;
  }

  /**
   * Add an element to the root of the log entry.
   *
   * @param \HighWire\Parser\DOMElementBase $elem
   *   A DOMElementBase to append the root of the Entry.
   *
   * @return self
   *   Return self for method chaining.
   */
  protected function AddElement(DOMElementBase $elem) {
    $elem->elem = $this->append($elem->elem);
    $elem->setDOM($this);
    return $this;
  }

}
