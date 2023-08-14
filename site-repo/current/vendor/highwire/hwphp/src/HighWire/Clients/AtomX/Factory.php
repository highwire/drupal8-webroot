<?php

namespace HighWire\Clients\AtomX;

use HighWire\Clients\ClientFactoryInterface;

/**
 * Custom factory for AtomX becuase it doesn't
 * use GuzzleClient as it's client backend.
 */
class Factory implements ClientFactoryInterface {

  /**
   * @inheritdoc
   */
  public static function get(array $config): AtomX {
    $uris = explode(',', $config['base_uri']);
    return new AtomX($uris, $config);
  }

}
