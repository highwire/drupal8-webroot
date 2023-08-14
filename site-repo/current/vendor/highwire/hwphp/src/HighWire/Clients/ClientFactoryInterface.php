<?php

namespace HighWire\Clients;

/**
 * Factory interface clients can implement if the default
 * ClientFactory class doesn't fit their needs.
 */
interface ClientFactoryInterface {

  /**
   * Get a client object.
   *
   * Clients are constructed using the client.config.yml.
   *
   * @param array $config
   *   The name of the service you are trying to instantiate.
   *
   * @return object
   *   A fully formed client object for the requested client
   */
  public static function get(array $config);

}
