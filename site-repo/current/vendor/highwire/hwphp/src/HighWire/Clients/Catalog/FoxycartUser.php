<?php

namespace HighWire\Clients\Catalog;

use GuzzleHttp\Client as GuzzleClient;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Clients\HWResponse;
use BetterDOMDocument\DOMDoc;


/**
 * Class FoxycartUser
 *
 * @package HighWire\Clients\Catalog
 */
class FoxycartUser extends Client {

  /**
   * The api token for the Foxycart store.
   *
   * @var string
   */
  protected $apiToken;

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
    $this->apiToken = $config['apiToken'];
  }

  /**
   * Get the user from the Foxycart.
   *
   * @param string $email
   *   The user's email.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getFoxycartUserAsync(string $email): Promise {
    $params = [];
    $params['form_params']['api_action'] = 'customer_get';
    $params['form_params']['api_token'] = $this->apiToken;
    $params['form_params']['customer_email'] = $email;

    $request = $this->buildRequest('POST', "/api");
    $http_promise = $this->sendAsync($request, $params);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $request = $http_promise->wait();
      $response = $request->getBody();
      $raw_response = $response->getContents();

      $foxycart_data_array = new DOMDoc($raw_response);
      $foxycart_user_response = $foxycart_data_array->getArray();

      $foxycart_data = $foxycart_user_response['foxydata'][0];

      $customer_id = FALSE;
      if (!empty($foxycart_data['result'][0]['#text'] === "SUCCESS")) {
        if (!empty($foxycart_data['customer_id'][0]['#text'])) {
          $customer_id = $foxycart_data['customer_id'][0]['#text'];
        }
      }

      $hw_response = new HWResponse($request, $customer_id);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Create a user record in Foxycart.
   *
   * @param string $email
   *   The user's email.
   * @param string $password_hash
   *   The user's password, as stored in the Drupal DB.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function createFoxycartUserAsync(string $email, string $password_hash): Promise {
    $params = [];
    $params['form_params']['api_action'] = 'customer_save';
    $params['form_params']['api_token'] = $this->apiToken;
    $params['form_params']['customer_email'] = $email;
    $params['form_params']['customer_password_hash'] = $password_hash;

    $request = $this->buildRequest('POST', "/api");
    $http_promise = $this->sendAsync($request, $params);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $request = $http_promise->wait();
      $response = $request->getBody();
      $raw_response = $response->getContents();

      $foxycart_data_array = new DOMDoc($raw_response);
      $foxycart_user_response = $foxycart_data_array->getArray();

      $foxycart_data = $foxycart_user_response['foxydata'][0];

      $customer_id = FALSE;
      if (!empty($foxycart_data['result'][0]['#text'] === "SUCCESS")) {
        if (!empty($foxycart_data['customer_id'][0]['#text'])) {
          $customer_id = $foxycart_data['customer_id'][0]['#text'];
        }
      }

      $hw_response = new HWResponse($request, $customer_id);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Add the user's data to custom fields on the cart.
   * These fields are needed to create licenses for purchased content.
   *
   * @param string $fcsid
   *   The foxycart session id for the user.
   * @param int $fc_customer_id
   *   The user's foxycart customer id.
   * @param int $hw_user_id
   *   The user's profile id from SAMSSigma.
   * @param string $profile_name
   *   The users display name, from their SAMSSigma profile.
   * @param string $sigma_client_id
   *   The SAMSSigma site id where the licenses should be created.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function enableFoxycartUsersCartAsync(string $fcsid, int $fc_customer_id, int $hw_user_id, string $profile_name, string $sigma_client_id): Promise {
    // Set the parameters to get the user's cart.
    $params = [];
    $params['h:hwUserId'] = $hw_user_id;
    $params['h:fccustid'] = $fc_customer_id;
    $params['h:hwLoginName'] = $profile_name;
    $params['h:siteId'] = $sigma_client_id;
    $params['fcsid'] = $fcsid;
    $params['output'] = 'json';
    $query = http_build_query($params);

    $request = $this->buildRequest('GET', "/cart?" . $query);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $request = $http_promise->wait();
      $response = $request->getBody();
      $raw_response = $response->getContents();

      $hw_response = new HWResponse($request, $raw_response);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Get a user's cart.
   *
   * @param string $fcsid
   *   The Foxycart session id for the user.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getFoxycartUserCartAsync(string $fcsid): Promise {

    // Set the parameters to get the user's cart.
    $params = [];
    $params['cart'] = 'view';
    $params['output'] = 'json';
    $params['fcsid'] = $fcsid;
    $query = http_build_query($params);

    $request = $this->buildRequest('GET', "/cart?" . $query);
    $http_promise = $this->sendAsync($request, $params);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $request = $http_promise->wait();
      $response = $request->getBody();
      $raw_response = $response->getContents();

      $hw_response = new HWResponse($request, $raw_response);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Update the items in a user's cart.
   *
   * @param string $fcsid
   *   The Foxycart session id for the user.
   * @param array $items
   *   An array of item objects with updated quantity.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function updateFoxycartUserCartAsync(string $fcsid, array $items): Promise {

    // Set the parameters to update the user's cart.
    $params = [];
    $params['cart'] = 'update';
    $params['output'] = 'json';
    $params['fcsid'] = $fcsid;

    // Add parameters for updating individual items.
    $key = 1;
    foreach ($items as $item) {
      $params[$key . ':id'] = $item->id;
      $params[$key . ':quantity'] = $item->quantity;
      $key++;
    }

    $query = http_build_query($params);

    $request = $this->buildRequest('GET', "/cart?" . $query);
    $http_promise = $this->sendAsync($request, $params);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $request = $http_promise->wait();
      $response = $request->getBody();
      $raw_response = $response->getContents();

      $hw_response = new HWResponse($request, $raw_response);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Reset a user's cart.
   *
   * @param string $fcsid
   *   The Foxycart session id for the user.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function resetFoxycartUserCartAsync(string $fcsid): Promise {

    // Set the parameters to get the user's cart.
    $params = [];
    $params['empty'] = 'reset';
    $params['output'] = 'json';
    $params['fcsid'] = $fcsid;
    $query = http_build_query($params);

    $request = $this->buildRequest('GET', "/cart?" . $query);
    $http_promise = $this->sendAsync($request, $params);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $request = $http_promise->wait();
      $response = $request->getBody();
      $raw_response = $response->getContents();

      $hw_response = new HWResponse($request, $raw_response);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Make a request to the cart.
   *
   * @param array $params
   *   An array of query parameters for the request.
   *   @see https://wiki.foxycart.com/v/2.0/cart
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function foxycartCartRequestAsync(array $params): Promise {
    $request = $this->buildRequest('GET', "/cart?");
    $http_promise = $this->sendAsync($request, $params);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $request = $http_promise->wait();
      $response = $request->getBody();
      $raw_response = $response->getContents();

      $hw_response = new HWResponse($request, $raw_response);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

}
