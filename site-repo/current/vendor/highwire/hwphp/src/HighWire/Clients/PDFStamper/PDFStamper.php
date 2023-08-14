<?php

namespace HighWire\Clients\PDFStamper;

use Psr\Http\Message\RequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Request;
use HighWire\Clients\Atom\Atom;
use HighWire\Clients\HWResponse;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Utility\Str;

/**
 * PDF Stamper Service Client Class.
 *
 * @method \HighWire\Clients\HWResponse getPDFExtract($apath)
 */
class PDFStamper extends Client {

  /**
   * Pass these headers from the client to the PDF Stamper service.
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
    'Accept-Ranges',
    'X-HighWire-User',
    'X-Firenze-Referrer',
    'X-Firenze-Proxy-Client'
  ];

  /**
   * Proxy a request to the PDF Stamper service, streaming the result to the client directly.
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
      throw new \Exception('PDF Stamper Service: Proxy method not allowed: ' . $method);
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
   * Construct a sass URI from an apath.
   *
   * @param string $apath
   *   Atom apth.
   *
   * @return string
   *   The sass URI
   */
  public static function getURI($apath) {
    return 'stamper:/' . $apath;
  }

  /**
   * Get an apath from a stamper URI.
   *
   * @param string $uri
   *   URI in the form of'stamper://corpus/1/2/3.full.pdf'
   *
   * @return string
   *   The apath
   */
  public static function parseURI($uri) {
    return substr($uri, 9);
  }

  /**
   * @param string $apath
   *   An Atom path, with or without the .atom extension.
   *
   * @return string
   *   A valid PDF path.
   */
  protected function processApath(string $apath) {
    $apath = ltrim($apath, '/');
    return $apath;
  }

}
