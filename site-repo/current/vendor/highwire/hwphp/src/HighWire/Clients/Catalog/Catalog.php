<?php

namespace HighWire\Clients\Catalog;

use GuzzleHttp\Client as GuzzleClient;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Clients\HWResponse;

/**
 * Class Catalog
 *
 * @package HighWire\Clients\Catalog
 */
class Catalog extends Client {

  const INTERVAL_FORMAT = 'P%yY%mM%dDT%hH%iM%sS';
  const PERPETUAL_INTERVAL = 'P999Y999M999DT0H0M0S';

  /**
   * The publisherID that's used to generate the request uri.
   * Ie. /offers/[publisherId]
   *
   * @var string
   */
  protected $publisherId;

  /**
   * Create a new client object.
   *
   * @param \GuzzleHttp\Client $http_client
   *   A guzzle client object.
   * @param array $config
   *   Any configuration the client needs.
   */
  public function __construct(GuzzleClient $http_client, array $config = []) {
    parent::__construct($http_client, $config);
    $this->publisherId = $config['publisherId'];
  }

  /**
   * Get an extract policy definition.
   *
   * @param array $item_ids
   *   Ids of items to get offer info for.
   *
   * @param bool $with_ancestors
   *   Flag to include ancestor pricing info for requested item.
   *
   * @param bool $with_collections
   *   Flag to include pricing info for associated collections.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getOfferAsync(array $item_ids = [], bool $with_ancestors = FALSE, bool $with_collections = FALSE): Promise {

    if (empty($item_ids)) {
      return new Promise();
    }

    $query = "?uri=";

    $query .= implode('&uri=', $item_ids);

    if (empty($with_ancestors)) {
      $query .= "&with-ancestors=no";
    }

    if (empty($with_collections)) {
      $query .= "&with-collections=no";
    }

    $request = $this->buildRequest('GET', "offers/" . $this->publisherId . $query);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $resp_body = $resp->getBody();
      $offers = json_decode($resp_body, TRUE);
      $offer_object = new Offer($offers);
      $hw_response = new HWResponse($resp, $offer_object);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Get the default currency for the publisher.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be fulfilled by calling wait()
   */
  public function getDefaultCurrencyAsync(): Promise {
    $request = $this->buildRequest('GET', "publishers/" . $this->publisherId . "/defaultcurrency");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $currency = $resp->getBody();
      $currency = json_decode($currency);
      $hw_response = new HWResponse($resp, $currency->currency);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Get the default currency for the publisher.
   *
   * @param string $user_ip
   *   The ip address of the user, example - bmjpg.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getUserCurrencyAsync(string $user_ip): Promise {
    $request = $this->buildRequest('GET', "publishers/" . $this->publisherId . "/preferredcurrency/$user_ip");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $currency = $resp->getBody();
      $currency = json_decode($currency);
      $hw_response = new HWResponse($resp, $currency->currency);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

}
