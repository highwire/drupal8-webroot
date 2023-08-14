<?php

namespace HighWire\Clients\Sass;

use Psr\Http\Message\RequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Request;
use HighWire\Clients\Atom\Atom;
use GuzzleHttp\Promise\Promise;

/**
 * Sass Service Client Class.
 */
class Sass extends Atom {

  /**
   * Proxy a request to the sass service, streaming the result to the client directly.
   *
   * @param string $apath
   *   The apath of the resource.
   * @param \Psr\Http\Message\RequestInterface $client_request
   *   The request from the end client.
   * @param array $additional_headers
   *   Additional headers to return to the client along with the binary response.
   * @param bool $exit_on_done
   *   Close the response and exit the process when done serving the binary.
   * @param string $auth_token
   *   The auth token to pass for embargoed content.
   * @param bool $embargoed
   *   A flag for when content may be embargoed, ensure unsuccessful responses
   *   are only retried once.
   * @param bool $edge_cache
   *   Allow this resource to be cached at the edge (eg in CloudFlare).
   *   This will pass the ETag header back to sass, and tag on "Cache-Control: Public, s-maxage=0".
   *   This means the resource can be cached on the edge, but should revalidated everytime.
   *
   * @example
   *   use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
   *   $psr7Factory = new DiactorosFactory();
   *   $request = $psr7Factory->createRequest(\Drupal::request());
   *   $binary_service->proxy($apath, $request);
   *   exit;
   */
  public function proxy($apath, RequestInterface $client_request = NULL, array $additional_headers = [], $exit_on_done = FALSE, $auth_token = '', $embargoed = FALSE, $edge_cache = FALSE) {
    // Create a default client if not provided.
    if (empty($client_request)) {
      // We create it as a symfony client, then cast it as a psr-7 client.
      $symfony_request = Request::create('http://localhost');
      $psr7_factory = new DiactorosFactory();
      $client_request = $psr7_factory->createRequest($symfony_request);
    }

    $method = $client_request->getMethod();
    if ($method != 'GET' && $method != 'HEAD') {
      throw new \Exception('Sass Service: Proxy method not allowed: ' . $method);
    }

    // Build a list of passed header values.
    $headers = [];
    foreach ($this->passedHeaders as $header) {
      if ($client_request->hasHeader($header)) {
        $headers[$header] = $client_request->getHeader($header);
      }
    }

    // Pass ETag if we are caching on the edge (CloudFlare)
    if ($edge_cache && $client_request->hasHeader('ETag')) {
      $headers['ETag'] = $client_request->getHeader('ETag');
    }

    if ($embargoed && !empty($auth_token)) {
      $headers['cookie'] = $auth_token;
    }

    // Do the request to the sass service.
    $request = $this->buildRequest($method, $apath, NULL, $headers);
    try {
      $resp = $this->send($request);
    }
    catch (\Exception $e) {
      // The request failed.  This could be because the requested item is
      // embargoed.  Make another request and include the auth token to fetch
      // the embargoed content.
      if ($e->getCode() >= 400 && !empty($auth_token) && !$embargoed) {
        $this->proxy($apath, NULL, [], FALSE, $auth_token, TRUE);
      }
      exit(0);
    }

    // Build the client response.
    $headers = $resp->getHeaders();
    $headers = array_merge($headers, $additional_headers);
    $response = new StreamedResponse(NULL, $resp->getStatusCode(), $headers);
    $response->setCallback(function () use ($resp) {
      $body = $resp->getBody();
      while (!$body->eof()) {
        print($body->read(1024));
        flush();
      }
      flush();
    });

    // If we have edge-caching turned on, set headers to allow caching at the edge with revalidation.
    if ($edge_cache) {
      $response->headers->set('Cache-Control', "public, max-age=300, s-maxage=0");
    }

    // Stream the response and exit.
    $response->send();
    if ($exit_on_done) {
      exit(0);
    }
  }

  /**
   * Construct a sass URI from an apath.
   *
   * @param string $apath
   *   Atom apth.
   *
   * @return string
   *   The sass URI
   */
  public static function getURI($apath): string {
    return 'sass:/' . $apath;
  }

  /**
   * Get an apath from a sass URI.
   *
   * @param string $uri
   *   URI in the form `sass://corpus/1/2/3.png`.
   *
   * @return string
   *   The apath
   */
  public static function parseURI($uri): string {
    return substr($uri, 6);
  }

}
