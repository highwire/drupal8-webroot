<?php

namespace HighWire\Clients\Links;

use HighWire\Clients\Client;
use HighWire\Clients\HWResponse;
use HighWire\Parser\Links\ISILinks;
use HighWire\Parser\Links\ScopusLinks;
use HighWire\Parser\Links\CrossRefLinks;
use HighWire\Parser\Links\IJLinks;
use HighWire\Parser\Links\GlencoeLinks;
use GuzzleHttp\Promise\Promise;
use BetterDomDocument\DOMDoc;

/**
 * links.highwire.org Client
 */
class Links extends Client {

  /**
   * Get an externalref link for a resource.
   *
   * @param string $apath
   *   The apath to the resource.
   * @param string $link_type
   *   Link type. Can be one of: ISI-CITING, PUBMED, PERMISSIONDIRECT, ENTREZLINKS, AUTHORSEARCH.
   * @param array $params
   *   Associative array of additional paramaters to pass, varies by $link_type. Examples:
   *     ISI-CITING: ['access_num' => 'plantcell;22/11/3509']
   *     PUBMED: ['access_num' => '21258123']
   *     ENTREZLINKS: ['id' => 'pubmed_gene', 'pmid' => '21097709']
   *     AUTHORSEARCH : ['access_num' => 'Xiao Z']
   *   .
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise.
   */
  public function externalRefAsync(string $apath, string $link_type, array $params = []): Promise {
    
    $query = ['link_type' => $link_type];
    $query = array_merge($query, $params);

    $request = $this->buildRequest('GET', '/externalref' . $apath . '?' . http_build_query($query));

    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();

      $dom_doc = new DomDoc(strval($resp->getBody()));

      $link = $dom_doc->xpathSingle("//atom:link");
      if ($link) {
        $link = $link->getAttribute('href');
      }

      $hw_response = new HWResponse($resp, $link);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Get ISI links for a resource
   *
   * @param string $apath
   *   The apath to the resource.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise containing an ISILinks object.
   */
  public function isiAsync(string $apath) {
    $request = $this->buildRequest('GET', '/isi' . $apath);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $isilinks = new ISILinks(strval($resp->getBody()));
      $isilinks->setResponse($resp);
      $promise->resolve($isilinks);
    });

    return $promise;
  }

  /**
   * Get Scopus links for a resource
   *
   * @param string $apath
   *   The apath to the resource.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise containing a ScopusLinks object.
   */
  public function scopusAsync(string $apath) {

    // For some moronic reason we strip .atom for scopus links request
    $apath = preg_replace('/\.atom$/', '', $apath);

    $request = $this->buildRequest('GET', '/scopus' . $apath);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $isilinks = new ScopusLinks(strval($resp->getBody()));
      $isilinks->setResponse($resp);
      $promise->resolve($isilinks);
    });

    return $promise;
  }

  /**
   * Get crossref links for a resource
   *
   * @param string $apath
   *   The apath to the resource.
   * @param string $login
   *   Crossref login.
   * @param string $password
   *   Crossref password.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise containing an CrossRefLinks object.
   */
  public function crossrefAsync(string $apath, string $login, string $password) {
    $request = $this->buildRequest('GET', '/crossref' . $apath . '?login-id=' . $login . '&login-passwd=' . $password);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $isilinks = new CrossRefLinks(strval($resp->getBody()));
      $isilinks->setResponse($resp);
      $promise->resolve($isilinks);
    });

    return $promise;
  }

  /**
   * Get inter-journal links for a resource
   *
   * @param string $apath
   *   The apath to the resource.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise containing an IJLinks object.
   */
  public function ijlinksAsync(string $apath) {
    $request = $this->buildRequest('GET', '/ijlinks?atom=' . $apath);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $isilinks = new IJLinks(strval($resp->getBody()));
      $isilinks->setResponse($resp);
      $promise->resolve($isilinks);
    });

    return $promise;
  }

  /**
   * Get glencoe links for a resource
   *
   * @param string $apath
   *   The apath to the resource.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise containing an GlencoeLinks object.
   */
  public function glencoeAsync(string $apath) {

    // strip .atom for glencoe links request
    $apath = preg_replace('/\.atom$/', '', $apath);
      
    $request = $this->buildRequest('GET', '/glencoe' . $apath);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $isilinks = new GlencoeLinks(strval($resp->getBody()));
      $isilinks->setResponse($resp);
      $promise->resolve($isilinks);
    });

    return $promise;
  }

}
