<?php

namespace HighWire\Clients\StarLogger;

use HighWire\Clients\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use GuzzleHttp\Promise\Promise;

/**
 * StarLogger client is used to connect to the Counter4/STAR service.
 */
class StarLogger extends Client {

  /**
   * Handle STAR Request.
   *
   * @param array $star_request
   *   The data array for the STAR request.
   * @param Symfony\Component\HttpFoundation\Request $request
   *   The request from the end client.
   * 
   *   Note this method takes the star request data
   *   array and calls additional methods for building,
   *   processing and sending the request.
   */
  public function handleStarRequestAsync(array $star_request, Request $request) {
    if (empty($star_request)) {
      throw new \Exception('Star request is empty.');
      return;
    }

    // There should always be a referrer, the service depends on it.
    // If it's empty, then set it to the site base url.
    if (empty($star_request['referer'])) {
      $host = $request->getHost();
      $star_request['referer'] = $host;
    }

    // Check some individual components required for the request.
    // api key and referer. CustomerId is always set.
    if (empty($star_request['platformapikey']) || empty($star_request['referer'])) {
      throw new \Exception('Star request apikey and referer are empty.');
      return;
    }

    // Convert our data array into a query string.
    $star_request_string = $this->buildStarRequestString($star_request);

    if (empty($star_request_string)) {
      throw new \Exception('Star request string is empty.');
    }

    // Send the request to the STAR service.
    try {
      $this->sendStarRequest($star_request_string);
    }
    catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }

    return;

  }

  /**
   * Build STAR Request String.
   *
   * @param array $star_request
   *   The data array for the STAR request.
   *
   * @return bool|string
   *   Star request string.
   *
   * @throws \Exception
   */
  protected function buildStarRequestString(array $star_request) {
    if (empty($star_request)) {
      return FALSE;
    }
    // Generate a string of key value pairs for the request.
    $star_request_string = http_build_query($star_request);

    if (!empty($star_request_string)) {
      return $star_request_string;
    }

    return FALSE;

  }

  /**
   * Send STAR Request.
   *
   * @param string $star_request_string
   *   The URI string to be sent to STAR.
   * 
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  protected function sendStarRequestAsync($star_request_string): Promise {

    // Get configured STAR service url.
    $counter_config = $this->getGuzzleConfig();
    $star_receiver = $counter_config['base_uri']->__toString();

    $url = $star_receiver . '?' . $star_request_string;
    $request = $this->buildRequest('GET', $url);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $promise->resolve($resp);
    });

    return $promise;
    
  }

  /**
   * Helper function for getting the 
   * sequence number of the event.
   */
  public function getSequenceNumber() {
    static $seq = 0;
    $seq++;
    return $seq;
  }

  /**
   * Helper function for determining if User-Agent
   * is mobile.
   * 
   * @param string $user_agent
   *   String representation of the user-agent attribute.
   * 
   * @return bool
   *   True if is mobile, false if not
   */
  public function isMobile($user_agent) {
    $is_mobile = FALSE;

    if (strpos($user_agent, 'Mobile') !== FALSE) {
      $is_mobile = TRUE;
    }

    return $is_mobile;
  }

}
