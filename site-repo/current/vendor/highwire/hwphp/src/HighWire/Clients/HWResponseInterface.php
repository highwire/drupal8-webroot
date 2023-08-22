<?php

namespace HighWire\Clients;

use Psr\Http\Message\ResponseInterface;

/**
 * A wrapper class that wraps a guzzle response object.
 */
interface HWResponseInterface extends ResponseInterface {

  /**
   * Get the parsed data returned from the http response.
   *
   * @return mixed
   *   Get the parsed data fromm the http request
   */
  public function getData();

}
