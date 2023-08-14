<?php

namespace HighWire\Clients\Staticfs;

use HighWire\Clients\Client;
use HighWire\Clients\HWResponse;
use GuzzleHttp\Promise\Promise;
use BetterDomDocument\DOMDoc;

/**
 * staticfs.highwire.org Client
 */
class Staticfs extends Client {

  /**
   * Get most-frequently-cited articles for a corpus.
   *
   * @param string $corpus
   *   The corpus-code.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise, result has array of apaths.
   */
  public function mostCitedAsync(string $corpus): Promise {
    return $this->ProcessAtomFeedRequest($corpus . '/reports/mfc_atom.xml');
  }

  /**
   * Get most-frequently-read articles for a corpus.
   *
   * @param string $corpus
   *   The corpus-code.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise, result has array of apaths.
   */
  public function mostReadAsync(string $corpus): Promise {
    return $this->ProcessAtomFeedRequest($corpus . '/reports/mfr_atom.xml');
  }

  /**
   * Many staticfs requests are nearly identical: get a list of apaths from an atom feed.
   * 
   * We replicate common behavior here.
   *
   * @param string $uri
   *   StaticFS URI.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise.
   */
  private function processAtomFeedRequest(string $uri): Promise {
    $request = $this->buildRequest('GET', $uri);

    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $dom_doc = new DomDoc(strval($resp->getBody()));

      $result = [];
      $links = $dom_doc->xpath("//feed:entry/feed:link[@rel='self']");
      if (!empty($links)) {
        foreach ($links as $link) {
          $result[] = $link->getAttribute('href');
        }
      }

      $hw_response = new HWResponse($resp, $result);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

}
