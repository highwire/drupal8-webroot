<?php

namespace HighWire\Clients\ThreePlayPlugin;

use GuzzleHttp\Client as GuzzleClient;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Clients\HWResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * 3Play Plugin client class.
 *
 * @package  HighWire\Clients\ThreePlayPlugin
 */
class ThreePlayPlugin extends Client {

  /**
   * Create a new client object.
   *
   * @param \GuzzleHttp\Client $http_client
   *   A guzzle client object.
   */
  public function __construct(GuzzleClient $http_client) {
    parent::__construct($http_client);
  }

  /**
   * Get 3Play Transcript file information for the Brightcove video.
   *
   * @param string $bc_video_id
   *   The Brightcove video id.
   * @param string $api_key
   *   The 3Play Plugin API Key.
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getBCVideoTranscriptDetailsAsync(string $bc_video_id, string $api_key) {
    if (empty($bc_video_id)) {
      throw new \Exception("Video ID is empty.", 1);
    }

    $request = $this->buildRequest('GET', "/v3/transcripts/?api_key=" . $api_key . "&media_file_reference_id=" . $bc_video_id);
    $http_promise = $this->sendAsync($request);

    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $promise->resolve($resp->getBody());
    });

    return $promise;
  }

}
