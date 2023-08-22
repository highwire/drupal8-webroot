<?php

namespace HighWire\Clients\PDFExtract;

use Psr\Http\Message\RequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Request;
use HighWire\Clients\HWResponse;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Utility\Str;

/**
 * PDF Extract Service Client Class.
 *
 * @method \HighWire\Clients\HWResponse getPDFExtract($apath)
 */
class PDFExtract extends Client {

  /**
   * Pass these headers from the client to the PDF Extract service.
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
   * HEAD a PDF Extract.
   *
   * @param string $apath
   *   The Atom path as a string.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise.
   */
  public function headPDFExtractAsync(string $apath): Promise {
    $apath = $this->processApath($apath);
    $request = $this->buildRequest('HEAD', $apath);
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
   * Proxy a request to the PDF Extract service, streaming the result to the client directly.
   *
   * @param string $apath
   *   The apath of the resource.
   * @param \Psr\Http\Message\RequestInterface $client_request
   *   The request from the end client.
   * @param array $additional_headers
   *   Additional headers to return to the client along with the PDF Extract response.
   * @param bool $exit_on_done
   *   Close the response and exit the process when done serving the PDF Extract.
   *
   * @throws \Exception
   *
   * @example
   *   use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
   *   $psr7Factory = new DiactorosFactory();
   *   $request = $psr7Factory->createRequest(\Drupal::request());
   *   $pdfextract_service->proxy($apath, $request);
   *   exit;
   */
  public function proxy($apath, RequestInterface $client_request = NULL, array $additional_headers = [], $exit_on_done = FALSE) {
    $apath = $this->processApath($apath);
    // Create a default client if not provided.
    if (empty($client_request)) {
      // We create it as a symfony client, then cast it as a psr-7 client.
      $symfony_request = Request::create('http://localhost');
      $psr7_factory = new DiactorosFactory();
      $client_request = $psr7_factory->createRequest($symfony_request);
    }
    $method = $client_request->getMethod();
    if ($method != 'GET' && $method != 'HEAD') {
      throw new \Exception('PDF Extract Service: Proxy method not allowed: ' . $method);
    }
    // Build a list of passed header values.
    $headers = [];
    foreach ($this->passedHeaders as $header) {
      if ($client_request->hasHeader($header)) {
        $headers[$header] = $client_request->getHeader($header);
      }
    }
    // Do the request to the PDF Extract service.
    $request = $this->buildRequest($method, $apath, NULL, $headers);
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
    // Stream the response and exit.
    $response->send();
    if ($exit_on_done) {
      exit(0);
    }
  }

  /**
   * @param string $apath
   *   An Atom path, with or without the .atom extension.
   *
   * @return string
   *   A valid PDF Extract apath.
   */
  protected function processApath(string $apath) {
    if (!Str::endsWith('.atom', $apath)) {
      $apath = $apath . '.atom';
    }
    $apath = str_replace('.atom', '.full.pdf', $apath);
    $apath = ltrim($apath, '/');
    return $apath;
  }

}
