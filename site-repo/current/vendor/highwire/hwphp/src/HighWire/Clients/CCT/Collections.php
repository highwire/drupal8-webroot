<?php

namespace HighWire\Clients\CCT;

use GuzzleHttp\Client as GuzzleClient;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Clients\HWResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class Collections
 *
 * @package HighWire\Clients\CCT
 */
class Collections {

  /**
   * Collections items array.
   *
   * @var array
   */
  protected $collections;



  /**
   * Collections constructor.
   *
   * @param array $collections_data
   *   Return response from CCT service.
   */
  public function __construct(array $collections_data) {
    foreach ($collections_data as $collection) {
      $collection = new Collection($collection);
      $this->addCollection($collection, $collection->getId());
    }
  }

  /************************************
   * Getters and Setters.             *
   ************************************/

  /**
   * @param Collection $member
   *   Collection to add to items array.
   *
   * @param string $key
   *   An id to be used as a key for the Collection.
   * 
   * @return void
   */
  public function addCollection(Collection $collection, $key = NULL) {
    if (!empty($key)) {
      $this->collections[$key] = $collection;
    }
    else {
      $this->collections[] = $collection;
    }
  }

  /**
   * Get all of the Collections for the publisher.
   * 
   * @return array
   *  An arry of Collection objects.
   */
  public function getAllCollections() {
    return $this->collections;
  }

  /**
   * Get the published collections for the publisher.
   * 
   * @return array
   *  An arry of Collection objects, or an empty array if there are none.
   */
  public function getPublishedCollections() {
    $published_collections = [];
    foreach ($this->collections as $collection) {
      $published = $collection->isPublished();
      if ($published) {
        $published_collections[] = $collection;
      }
    }

    return $published_collections;
  }

}