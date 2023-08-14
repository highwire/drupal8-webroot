<?php 

namespace HighWire\Clients\Ecommerce;

use HighWire\Clients\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Promise\Promise;
use HighWire\Clients\HWResponse;

/**
 * Transactions Client Class.
 */
class Transactions extends Client {

  /**
   * The publisherID that's used to generate the request uri.
   * Ie. /publishers/[publisherId]/transactions
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
   * @param string $start_date
   *   Start date for fetching transactions.
   * @param string $end_date
   *   End date for fetching transactions.
   * @param string $email
   *   (Optional) Email ID for filtering transactions.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a Guzzle promise object that can be fulfilled by calling wait().
   */
  public function getTransactionsAsync(string $start_date, string $end_date, string $email = ""): Promise {
    if (empty($start_date) || empty($end_date)) {
      return new Promise();
    }

    $query = "?startDate=" . urlencode($start_date);
    $query .= "&endDate=" . urlencode($end_date);

    if (!empty($email)) {
      $query .= "&emailId=" . urlencode($email);
    }

    $request = $this->buildRequest('GET', "publishers/" . $this->publisherId . "/transactions" . $query);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $resp_body = $resp->getBody();
      $transactions = json_decode($resp_body, true);
      $hw_response = new HWResponse($resp, $transactions['transactions']);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

}
