<?php

namespace HighWire\Clients\Taxonomy;

use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Parser\Taxonomy\TaxonomyTree;
use HighWire\Parser\Taxonomy\TaxonomyTreeList;

/**
 * Taxonomy service Client.
 *
 * {@inheritdoc}
 *
 * @method \HighWire\Parser\Taxonomy\TaxonomyTree getTree(string $publisher, string $collection, string $scheme)
 * @method \HighWire\Parser\Taxonomy\TaxonomyTreeList getTreeList(string $publisher)
 */
class Taxonomy extends Client {

  /**
   * Always ask for the XML respons from the taxonomy service.
   *
   * {@inheritdoc}
   */
  protected $headers = ['Accept' => 'application/vnd.hw-taxonomy+xml'];

  /**
   * Get taxonomy tree.
   *
   * @param string $publisher
   *   The publiser code.
   * @param string $collection
   *   Collection. Generally this is "content".
   * @param string $scheme
   *   The taxonomy scheme, for example "subject".
   *
   * @return \GuzzleHttp\Promise\Promise
   *   A guzzle response object.
   */
  public function getTreeAsync(string $publisher, string $collection, string $scheme): Promise {
    $uri = "tree/$publisher/$collection/$scheme";
    $request = $this->buildRequest('GET', $uri);
    $http_promise = $this->sendAsync($request);

    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $taxonomy = new TaxonomyTree($resp->getBody());
      $taxonomy->setResponse($resp);
      $promise->resolve($taxonomy);
    });

    return $promise;
  }

  /**
   * List taxonomy trees for a publisher
   *
   * @param string $publisher
   *   The publiser code.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   A guzzle response object.
   *   Note that the trees returned are stubs (no terms), you will need to call getTree()
   *   to get the whole tree with terms.
   */
  public function getTreeListAsync(string $publisher): Promise {
    $uri = "tree/$publisher";
    $request = $this->buildRequest('GET', $uri);
    $http_promise = $this->sendAsync($request);

    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $taxonomy_tree_list = new TaxonomyTreeList($resp->getBody());
      $taxonomy_tree_list->setResponse($resp);
      $promise->resolve($taxonomy_tree_list);
    });

    return $promise;
  }

}
