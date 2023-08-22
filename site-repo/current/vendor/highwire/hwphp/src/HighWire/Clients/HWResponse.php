<?php

namespace HighWire\Clients;

use GuzzleHttp\Psr7\Response;

/**
 * A wrapper class that wraps a guzzle response object.
 */
class HWResponse implements HWResponseInterface {

  use ResponseTrait;

  protected $data;

  /**
   * Create a new HWResponse object.
   *
   * @param \GuzzleHttp\Psr7\Response $response
   *   A guzzle psr7 response obejct.
   * @param mixed $data
   *   The data returned from a guzzle response.
   *   This is the variable that holds the parsed response.
   */
  public function __construct(Response $response, $data) {
    $this->setResponse($response);
    $this->data = $data;
  }

  /**
   * Get the parsed data returned from the http response.
   *
   * @return mixed
   *   Get the parsed data fromm the http request
   */
  public function getData() {
    return $this->data;
  }

  /**
   * Set the data for the response object.
   *
   * @param mixed $data
   *   The response data from the client request.
   */
  public function setData($data) {
    $this->data = $data;
  }

}
