<?php

namespace HighWire\FreebirdSchema;

use HighWire\Parser\ExtractPolicy\ExtractPolicy;

/**
 * The Schema service interface defines the contract for implementing
 * a schema service.
 */
interface SchemaServiceInterface {

  /**
   * Get schema mapping for 'freebird' content.
   * 
   * @param \HighWire\Parser\ExtractPolicy\ExtractPolicy $extract_policy
   *   Extract policy object.
   *
   * @param string[] $corpora
   *   List of corpus codes.
   *
   * @return \HighWire\FreebirdSchema\Schema
   *   Returns an Freebird Schema object.
   */
  public function getFreebirdSchema(ExtractPolicy $extract_policy, array $corpora);

}
