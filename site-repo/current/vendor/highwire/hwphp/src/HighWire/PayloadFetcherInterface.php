<?php

namespace HighWire;

/**
 * Interface for being able to get payloads.
 */
interface PayloadFetcherInterface {

  /**
   * Get a single item by atom path.
   *
   * @param string $id
   *   Atom path. For example '/bmj/1/2/3.atom'.
   *
   * @param string $policy_name
   *   The name of the extract policy that was used to extract the ids.
   *
   * @return array
   *   Return a single data item as an array
   *
   * @throws \HighWire\Exception\HighWirePayloadNotFoundException;
   */
  public function get($id, $policy_name = NULL);

  /**
   * Get multiple items by atom path.
   *
   * @param array $ids
   *   Array of atom paths. For example ['/bmj/1/2/3.atom', '/sci/4/5/6.atom'].
   *
   * @param string $policy_name
   *   The name of the extract policy that was used to extract the ids.
   *
   * @return array
   *   An associatve array of items keyed by id. Each data item is also an associative array.
   *
   * @throws \HighWire\Exception\HighWirePayloadNotFoundException;
   */
  public function getMultiple(array $ids, $policy_name = NULL);

  /**
   * Get all payloads for a given corpus.
   *
   * @param string $corpus
   *   The corpus you want all the ids for.
   *
   * @param string $policy_name
   *   The name of the extract policy that was used to extract the ids.
   *
   * @return \Iterator
   *   Need to return a php Iterator
   *
   * @throws \HighWire\Exception\HighWireCorpusIdNotFoundException;
   */
  public function getCorpusIds($corpus, $policy_name = NULL);

}
