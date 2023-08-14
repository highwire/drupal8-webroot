<?php

namespace HighWire\Clients\Binary;

use Psr\Http\Message\RequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use HighWire\Clients\HWResponse;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

/**
 * Binary Service Client Class.
 *
 * @method \HighWire\Clients\HWResponse getBinary($binary_id)
 * @method \HighWire\Clients\HWResponse headBinary($binary_id)
 */
class Binary extends Client {

  /**
   * Pass these headers from the client to the binary service.
   *
   * @var array
   */
  protected $passedHeaders = [
    'If-None-Match',
    'If-Match',
    'If-Modified-Since',
    'If-Unmodified-Since',
    'If-Range',
    'Range',
  ];

  /**
   * Get a binary.
   *
   * @param string $binary_id
   *   The binary ID as a string.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise.
   */
  public function getBinaryAsync(string $binary_id): Promise {
    $request = $this->buildRequest('GET', "entity/$binary_id");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $hw_response = new HWResponse($resp, $resp->getBody());
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * HEAD a binary.
   *
   * @param string $binary_id
   *   The binary ID as a string.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise.
   */
  public function headBinaryAsync(string $binary_id): Promise {
    $request = $this->buildRequest('HEAD', "entity/$binary_id");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $data = [];
      foreach ($resp->getHeaders() as $name => $values) {
        $data[$name] = $values[0];
      }
      $hw_response = new HWResponse($resp, $data);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Get metadata about a single binary resource.
   *
   * @param string $binary_id
   *   The binary ID as a string.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise.
   */
  public function getMetadataAsync($binary_id): Promise {
    $request = $this->buildRequest('GET', "metadata/$binary_id");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $metadata = json_decode(strval($resp->getBody()), TRUE);
      $hw_response = new HWResponse($resp, $metadata);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Get metadata about a multiple binary resources.
   *
   * @param array $binary_ids
   *   Array of binary IDs.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   A guzzle promise
   *   getData() will return an array of metadata keyed by the binary_id
   */
  public function getMultipleMetadataAsync(array $binary_ids): Promise {
    $request = $this->buildRequest('POST', "metadata", json_encode($binary_ids));
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $metadata = [];
      foreach (json_decode(strval($resp->getBody()), TRUE) as $item) {
        $metadata[Binary::Id($item['ingestKey'], $item['binaryHash'])] = $item;
      }

      $hw_response = new HWResponse($resp, $metadata);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Proxy a request to the binary service, streaming the result to the client directly.
   *
   * @param string $binary_id
   *   The binary ID as a string.
   * @param \Psr\Http\Message\RequestInterface $client_request
   *   The request from the end client.
   * @param array $additional_headers
   *   Additional headers to return to the client along with the binary response.
   * @param bool $exit_on_done
   *   Close the response and exit the process when done serving the binary.
   * @param bool $edge_cache
   *   Allow this resource to be cached at the edge (eg in CloudFlare).
   *   This will pass the ETag header back to sass, and tag on "Cache-Control: Public, s-maxage=0".
   *   This means the resource can be cached on the edge, but should revalidated everytime.
   *
   * @example
   *   use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
   *   $psr7Factory = new DiactorosFactory();
   *   $request = $psr7Factory->createRequest(\Drupal::request());
   *   $binary_service->proxy($binary_id, $request);
   *   exit;
   */
  public function proxy($binary_id, RequestInterface $client_request = NULL, array $additional_headers = [], $exit_on_done = FALSE, $edge_cache = FALSE) {
    // Create the request to the binary service.
    $request = $this->prepareRequest($binary_id, $client_request, $edge_cache);
    $resp = $this->send($request);

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
   * Returns data from the binary service.
   *
   * @param string $binary_id
   *   The binary ID as a string.
   * @param \Psr\Http\Message\RequestInterface $client_request
   *   The request from the end client.
   * @param array $additional_headers
   *   Additional headers to return to the client along with the binary response.
   * @param bool $edge_cache
   *   Allow this resource to be cached at the edge (eg in CloudFlare).
   *   This will pass the ETag header back to sass, and tag on "Cache-Control: Public, s-maxage=0".
   *   This means the resource can be cached on the edge, but should revalidated everytime.
   *
   * @return Response
   *   The Symfony response.
   *
   * @example
   *   use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
   *   $psr7Factory = new DiactorosFactory();
   *   $request = $psr7Factory->createRequest(\Drupal::request());
   *   return $binary_service->fetchData($binary_id, $request);
   */
  public function fetchData($binary_id, RequestInterface $client_request = NULL, array $additional_headers = [], $edge_cache = FALSE) {
    // Create the request to the binary service.
    $request = $this->prepareRequest($binary_id, $client_request, $edge_cache);
    $resp = $this->send($request);

    // Build headers.
    $headers = $resp->getHeaders();
    $headers = array_merge($headers, $additional_headers);

    // If we have edge-caching turned on, set headers to allow caching at the edge with revalidation.
    if ($edge_cache) {
      $headers['Cache-Control'] = "public, max-age=300, s-maxage=0";
    }

    // Build new response.
    $body = $resp->getBody();
    $content = $body->read($body->getSize());
    $response = new Response($content, $resp->getStatusCode(), $headers);

    return $response;
  }

  /**
   * Construct a binary ID from ingest key and hash.
   *
   * @param string $ingest_key
   *   Ingest Key.
   * @param string $binary_hash
   *   Binary hash.
   *
   * @return string
   *   The binary ID
   */
  public static function Id($ingest_key, $binary_hash): string {
    return $ingest_key . '/' . $binary_hash;
  }

  /**
   * Construct a binary ID from a binary:// URI.
   *
   * @param string $uri
   *   URI in the form `binary://corpus/b889asdf78asdf/p90d8osod/filename.png`.
   *
   * @return string
   *   The binary ID
   */
  public static function binaryIdFromURI($uri): string {
    $parts = self::parseURI($uri);
    return self::id($parts['ingest_key'], $parts['binary_hash']);
  }

  /**
   * Construct a binary URI from an ingest key and binary hash.
   *
   * @param string $corpus
   *   Corpus code.
   * @param string $ingest_key
   *   Ingest key.
   * @param string $binary_hash
   *   Binary hash.
   * @param string $filename
   *   The filename.
   *
   * @return string
   *   The binary URI
   */
  public static function getURI($corpus, $ingest_key, $binary_hash, $filename): string {
    return 'binary://' . $corpus . '/' . self::Id($ingest_key, $binary_hash) . '/' . $filename;
  }

  /**
   * Construct a binary URI from an ingest key and binary hash.
   *
   * @param string $corpus
   *   Corupus Code.
   * @param string $ingest_key
   *   Ingest Key.
   * @param string $binary_hash
   *   Binary hash.
   * @param string $filename
   *   The filename.
   *
   * @return string
   *   The binary URI
   */
  public static function getBinaryURI($corpus, $ingest_key, $binary_hash, $filename = NULL): string {
    $id = self::Id($ingest_key, $binary_hash);
    return 'binary://' . $corpus . '/' . $id . (isset($filename) ? '/' . $filename : '');
  }

  /**
   * Parse a binary ID into ingest key and hash.
   *
   * @param string $binary_id
   *   The binary ID in the form `b889asdf78asdf/p90d8osod`.
   *
   * @return array
   *   Array with 'ingest_key' and 'binary_hash' keys.
   *   If applicable, the array may also contain the 'filename' key.
   */
  public static function parseId($binary_id): array {
    $parts = explode('/', $binary_id);
    $res = [
      'ingest_key' => $parts[0],
      'binary_hash' => $parts[1],
    ];
    if (!empty($parts[2])) {
      $res['filename'] = $parts[2];
    }
    return $res;
  }

  /**
   * Parse a binary URI into the individual components.
   *
   * @param string $uri
   *   URI in the form `binary://corpus/b889asdf78asdf/p90d8osod/filename.png`.
   *
   * @return array
   *   Array with 'corpus', 'ingest_key', 'binary_hash', and 'filename' keys
   */
  public static function parseURI($uri): array {
    $uri = parse_url($uri);
    $parts = self::parseId(ltrim($uri['path'], '/'));
    $parts['corpus'] = $uri['host'];

    return $parts;
  }

  /**
   * Prepare a request to the binary service.
   *
   * @param string $binary_id
   *   The binary ID as a string.
   * @param \Psr\Http\Message\RequestInterface $client_request
   *   The request from the end client.
   * @param bool $edge_cache
   *   Allow this resource to be cached at the edge (eg in CloudFlare).
   *   This will pass the ETag header back to sass, and tag on "Cache-Control: Public, s-maxage=0".
   *   This means the resource can be cached on the edge, but should revalidated everytime.
   *
   * @return GuzzleRequest;
   *   A prepared request.
   */
  protected function prepareRequest($binary_id, RequestInterface $client_request = NULL, $edge_cache = FALSE) {
    // Create a default client if not provided.
    if (empty($client_request)) {
      // We create it as a symfony client, then cast it as a psr-7 client.
      $symfony_request = Request::create('http://localhost');
      $psr7_factory = new DiactorosFactory();
      $client_request = $psr7_factory->createRequest($symfony_request);
    }

    $method = $client_request->getMethod();
    if ($method != 'GET' && $method != 'HEAD') {
      throw new \Exception('Binary Service: Proxy method not allowed: ' . $method);
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

    // Create the request to the binary service.
    $request = $this->buildRequest($method, "entity/" . $binary_id, NULL, $headers);

    return $request;
  }

}
