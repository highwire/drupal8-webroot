<?php

namespace HighWire\Clients\AtomLite;

use HighWire\Clients\Client;
use HighWire\PayloadFetcherInterface;
use HighWire\ExtractPolicyServiceInterface;
use HighWire\Clients\HWResponse;
use GuzzleHttp\Promise\Promise;
use HighWire\Exception\HighWirePolicyNotFoundException;
use HighWire\Exception\HighWireCorpusIdsNotFoundException;

/**
 * Atomlite client is used to connect to the atomlite service.
 */
class AtomLite extends Client implements PayloadFetcherInterface, ExtractPolicyServiceInterface {

  /**
   * Payload static cache.
   */
  protected static $payloadStaticCache = [];

  /**
   * {@inheritdoc}
   *
   * @see \HighWire\PayloadFetcherInterface::get()
   */
  public function get($id, $policy_name = NULL): array {
    if (empty($policy_name) && !empty($this->config['policy-name'])) {
      $policy_name = $this->config['policy-name'];
    }

    if (empty($policy_name)) {
      $this->throwException("Missing policy name");
    }

    // Check for promises that needs to be resolved
    if (!empty(self::$payloadStaticCache[$policy_name][$id]) && is_a(self::$payloadStaticCache[$policy_name][$id], '\GuzzleHttp\Promise\Promise')) {
      $response = self::$payloadStaticCache[$policy_name][$id]->wait();
      foreach ($response as $apath => $doc) {
        self::$payloadStaticCache[$policy_name][$apath] = $doc;
      }
    }

    if (!empty(self::$payloadStaticCache[$policy_name][$id])) {
      return self::$payloadStaticCache[$policy_name][$id];
    }

    $id = [$id];
    $payload = $this->getMultipleAsync($id, $policy_name, TRUE)->wait();

    if (is_null($payload)) {
      $payload = [];
    }

    return $payload;
  }

  /**
   * Make an asynchronous request to get a payload for a given id.
   *
   * @param string $id
   *   An id that relates to poyload in AtomLite. Usually an apath.
   * @param string $policy_name
   *   The name of the extract policy that relates to the payload of interest.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getAsync($id, $policy_name = NULL): Promise {
    if (empty($policy_name) && !empty($this->config['policy-name'])) {
      $policy_name = $this->config['policy-name'];
    }

    if (empty($policy_name)) {
      $this->throwException("Missing policy name");
    }

    $id = [$id];
    return $this->getMultipleAsync($id, $policy_name, TRUE);
  }

  /**
   * {@inheritdoc}
   *
   * @see \HighWire\PayloadFetcherInterface::getMultiple()
   */
  public function getMultiple(array $ids, $policy_name = NULL): array {
    if (empty($policy_name) && !empty($this->config['policy-name'])) {
      $policy_name = $this->config['policy-name'];
    }

    if (empty($policy_name)) {
      $this->throwException("Missing policy name");
    }

    // Check for promises that needs to be resolved
    foreach ($ids as $id) {
      if (!empty(self::$payloadStaticCache[$policy_name][$id]) && is_a(self::$payloadStaticCache[$policy_name][$id], '\GuzzleHttp\Promise\Promise')) {
        $response = self::$payloadStaticCache[$policy_name][$id]->wait();
        foreach ($response as $apath => $doc) {
          self::$payloadStaticCache[$policy_name][$apath] = $doc;
        }
      }
    }

    // Check static cache
    $idsNotCached = [];
    foreach ($ids as $id) {
      if (!isset(self::$payloadStaticCache[$policy_name][$id])) {
        $idsNotCached[] = $id;
      }
    }

    $payloads = [];
    $cachedIds = $ids;

    if (!empty($idsNotCached)) {
      $cachedIds = array_diff($ids, $idsNotCached);
      $payloads = $this->getMultipleAsync($idsNotCached, $policy_name)->wait();
      foreach ($payloads as $id => $payload) {
        self::$payloadStaticCache[$policy_name][$id] = $payload;
      }
    }

    // Fill in cached items
    foreach ($cachedIds as $id) {
      $payloads[$id] = self::$payloadStaticCache[$policy_name][$id];
    }

    return $payloads;
  }

  /**
   * Make an asynchronous request to get multiple payloads.
   *
   * @todo This should return a generator, we need to wait
   *   for platform to create a streaming api frist though.
   *
   * @param array $ids
   *   An array of ids. Usually apaths.
   * @param string $policy_name
   *   The name of the extract policy that relates to the payload of interest.
   * @param bool $single
   *   If true the first payload in the result array will be returned.
   * @param bool $cached
   *   If true, only payloads in the atomlite database will be returned.
   *   If false, and the payload doesn't exist in atomlite, then atomlite
   *   will do a life extract. PLATFORM1-1341.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getMultipleAsync(array $ids, $policy_name = NULL, $single = FALSE, $cached = TRUE): Promise {
    $uri = 'atoms';
    if ($cached) {
      $uri = $uri . '?only-if-cached=true';
    }

    if (empty($policy_name) && !empty($this->config['policy-name'])) {
      $policy_name = $this->config['policy-name'];
    }

    if (empty($policy_name)) {
      $this->throwException("Missing policy name");
    }

    $body = json_encode(array_values($ids));
    $request = $this->buildRequest('POST', $uri, $body, ['Accept' => $this->getMimeType($policy_name), 'Content-Type' => 'application/json']);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise, $single, $policy_name) {
      $resp = $http_promise->wait();
      $payloads = json_decode((string) $resp->getBody(), TRUE);
      $results = [];

      if (!empty($payloads)) {
        foreach ($payloads['resources'] as $payload) {
          $results[$payload['uri']] = $payload['resource'];
          self::$payloadStaticCache[$policy_name][$payload['uri']] = $payload['resource'];
        }
      }

      if ($payloads === NULL) {
        error_log("Error: AtomLite Response: Cannot deserialize JSON: $resp->getBody()");
      }

      if ($single) {
        $results = reset($results);
      }

      $promise->resolve($results);
    });

    foreach ($ids as $id) {
      self::$payloadStaticCache[$policy_name][$id] = $promise;
    }

    return $promise;
  }

  /**
   * Get a payload from atomlite
   *
   * @param string $id
   *   The id of the payload, usually the apath.
   * @param string $mime_type
   *   The mime type of the payload.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getPayloadAsync($id, $mime_type): Promise {
    // @note we may need to change this to allow paging.
    $request = $this->buildRequest('GET', "atom$id", NULL, ['Accept' => $mime_type]);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $payload = $resp->getBody();
      $hw_response = new HWResponse($resp, $payload);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * {@inheritdoc}
   *
   * @see \HighWire\PayloadFetcherInterface::getCorpusIds()
   */
  public function getCorpusIds($corpus, $policy_name = NULL): array {
    $ids = $this->getCorpusIdsAsync($corpus, $policy_name)->wait();

    if (empty($ids)) {
      throw new HighWireCorpusIdsNotFoundException("No ids found for $corpus and extract-policy $policy_name");
    }

    return $ids;
  }

  /**
   * Make an asynchronous request to all ids for a given corpus.
   *
   * @param string $corpus
   *   The corpus to get all the ids for.
   * @param string $policy_name
   *   The name of the extract policy that relates
   *   to where to find the corpus ids.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getCorpusIdsAsync($corpus, $policy_name = NULL): Promise {
    // @note we may need to change this to allow paging.
    $request = $this->buildRequest('GET', "corpus/$corpus/uris?mimeType=" . urlencode($this->getMimeType($policy_name)) . '&start=1&batchsize=1000000');;
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $data = json_decode((string) $resp->getBody(), TRUE);
      $ids = [];
      if (!empty($data)) {
        foreach ($data as $alite_id_record) {
          $ids[$alite_id_record['uri']] = $alite_id_record['uri'];
        }
      }
      $promise->resolve($ids);
    });

    return $promise;
  }

  /**
   * {@inheritdoc}
   *
   * @see \HighWire\ExtractPolicyServiceInterface::getRawExtractPolicy()
   */
  public function getRawExtractPolicy(string $policy_name) {
    $policy = $this->getRawExtractPolicyAsync($policy_name)->wait();
    if (empty($policy)) {
      throw new HighWirePolicyNotFoundException("Could not locate extract policy $policy_name");
    }
    return $policy;
  }

  /**
   * Make an asynchronous request to get the raw extract policy xml from atomlite.
   *
   * @param string $policy_name
   *   The name of the extract policy to get the xml for.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getRawExtractPolicyAsync(string $policy_name): Promise {
    $request = $this->buildRequest('GET', "policies");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise, $policy_name) {
      $resp = $http_promise->wait();
      $policies = json_decode((string) $resp->getBody(), TRUE);
      $source = '';
      if (!empty($policies)) {
        foreach ($policies as $policy) {
          if ($policy['name'] == $policy_name) {
            $source = $policy['source'];
            break;
          }
        }
      }

      $promise->resolve($source);
    });

    return $promise;
  }

  /**
   * Get the policies.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getPolicesAsync(): Promise {
    $request = $this->buildRequest('GET', "policies");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $policies = json_decode((string) $resp->getBody(), TRUE);
      $promise->resolve($policies);
    });

    return $promise;
  }

  /**
   * Delete an id and all it's payloads from atomlite.
   *
   * @param string $id
   *   An id to delete.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be fulfilled by calling wait()
   */
  public function deleteIdAsync($id): Promise {
    $request = $this->buildRequest('DELETE', "atom$id");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $hw_response = new HWResponse($resp, '');
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Get the mime type for a given extract policy.
   *
   * @todo Change this function to use getExtractPolcies
   *
   * @param string $policy_name
   *   The name of the extract policy.
   * @param string $extention
   *   The extension of the type of extract.
   *
   * @return string
   *   The generated mime type string
   */
  public function getMimeType($policy_name, $extention = 'json') {
    // If the policy already looks like a mime type then just return it.
    // This method was poor choice when things first got started.
    if (strpos($policy_name, 'application/') !== FALSE) {
      return $policy_name;
    }
    return "application/vnd.hw." . $policy_name . "+" . $extention;
  }

}
