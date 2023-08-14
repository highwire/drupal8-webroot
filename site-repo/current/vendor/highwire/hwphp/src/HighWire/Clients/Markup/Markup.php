<?php

namespace HighWire\Clients\Markup;

use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Clients\HWResponse;
use HighWire\Parser\Markup\Markup as MarkupParser;

/**
 * Markup Client Class.
 */
class Markup extends Client {

  /**
   * Get single markup profile's.
   *
   * @param string $apath
   *   The apath to get the markup for.
   * @param string $profile
   *   The profile name.
   * @param string $context
   *   The context name.
   * @param array $args
   *   Additional args for the profile or postprocessors.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   A guzzle response opbject.
   */
  public function getMarkupSingleAsync($apath, $profile, $context = 'hwphp', array $args = []): Promise {
    return $this->getMarkupMultipleAsync([$apath], $profile, $context, $args, TRUE);
  }

  /**
   * Get multiple markup profile's.
   *
   * @param array $apaths
   *   The apaths to get the markup for.
   * @param string|array $profiles
   *   The profile name.
   * @param string $context
   *   The context name.
   * @param array $args
   *   Additional args for the profile or postprocessors.
   * @param bool $single
   *   A flag indicating if a single response should be
   *   returned in the HWResponse data.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   A guzzle response opbject.
   */
  public function getMarkupMultipleAsync(array $apaths, $profiles, $context = 'hwphp', array $args = [], $single = FALSE): Promise {
    if (!is_array($profiles)) {
      $profiles = [$profiles];
    }

    $profiles = implode(",", $profiles);
    $args['only-if-cached'] = 'true';
    $uri = "markup/$context/$profiles";
    $args['only-if-cached'] = 'true';

    $uri = $uri . '?' . http_build_query($args);

    foreach ($apaths as $apath) {
      $apaths_url[] = 'src=' . $apath;
    }

    $uri .= '&' . implode("&", $apaths_url);

    $headers = [];
    // Only add the header for mulitple source/profiles.
    if ($single == FALSE) {
      $headers['Cache-Control'] = 'max-age=3600'; // 1 hour.
    }

    $request = $this->buildRequest('GET', $uri, NULL, $headers);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise, $apaths, $single) {
      $resp = $http_promise->wait();
      $code = $resp->getStatusCode();

      // No markup
      if ($code == 204) {
        $hw_response = new HWResponse($resp, NULL);
        $promise->resolve($hw_response);
      }
      else {
        $hw_response = NULL;
        $content_types = $resp->getHeader('Content-Type');
        if (strtolower($content_types[0]) == "application/xhtml+xml;charset=utf-8") {
          if ($single) {
            $markup = new MarkupParser($resp->getBody());
            $hw_response = new HWResponse($resp, $markup);
          }
          else {
            $markup = new MarkupParser($resp->getBody());
            $hw_response = new HWResponse($resp, [$apaths[0] => $markup]);
          }
        }
        elseif (strtolower($content_types[0]) == "application/vnd.collection+json;charset=utf-8") {
          $jsonData = json_decode($resp->getBody(), TRUE);
          $markups = [];
          foreach ($jsonData['collection']['items'] as $item) {
            foreach ($item['data'] as $data) {
              $markups[$data['profile-id']][$data['sourceUri']] = new MarkupParser($data['markup']);
            }
          }
          $hw_response = new HWResponse($resp, $markups);
        }

        if ($hw_response == NULL) {
          throw new \Exception("hw_response is NULL, which will result in any additional method calls to fail.");
        }

        $promise->resolve($hw_response);
      }
    });

    return $promise;
  }

  /**
   * Get the markup profile configurations from the markup service.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getProfilesAsync(): Promise {
    $request = $this->buildRequest('GET', "transprofiles");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
        $resp = $http_promise->wait();
        $hw_response = new HWResponse($resp, json_decode($resp->getBody(), TRUE));
        $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Purge markup cache for a given context.
   *
   * @param string $context
   *   The markup cache context.
   *
    * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function purgeAllAsync($context): Promise {
    if (empty($context)) {
      throw new \Exception("Can't purge markup cache because context is empty");
    }

    $uri = "markup/$context/";

    $request = $this->buildRequest('PURGE', $uri);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
        $resp = $http_promise->wait();
        $hw_response = new HWResponse($resp, NULL);
        $promise->resolve($hw_response);
    });

    return $promise;
  }

}
