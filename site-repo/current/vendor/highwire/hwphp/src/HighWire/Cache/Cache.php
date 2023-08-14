<?php

namespace HighWire\Cache;

use Sabre\Cache\Memory;

/**
 * Cache Utility Class.
 */
class Cache {

  /**
   * Return a PSR-16 static cache
   *
   * @param string $bin
   *   String name for cache bin.
   *
   * @return \Psr\SimpleCache\CacheInterface
   *   A cache object that adheres to PSR-16.
   *
   * @example
   *   // Get a value
   *   $value = Cache::Static('mybin')->get('cache-key-123');
   *
   *   // Set a value
   *   Cache::Static('mybin')->set('cache-key-456', $value);
   *
   *   // Clear all values
   *   Cache::Static('mybin')->clear();
   */
  public static function Static($bin = 'default') {
    static $caches = [];

    if (empty($caches[$bin])) {
      $caches[$bin] = new Memory();
    }

    return $caches[$bin];
  }

}
