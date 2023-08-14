<?php

use HighWire\Cache\Cache;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase {

  public function testCache() {

    $value = 'testvalue';
    $cache_key = 'testcacheKey';

    // Test empty
    $this->assertEmpty(Cache::Static('test')->get($cache_key));

    // Test set and get
    Cache::Static('test')->set($cache_key, $value);
    $this->assertEquals(Cache::Static('test')->get($cache_key), $value);

    // Assert that unrelated bin is empty
    $this->assertEmpty(Cache::Static('otherbin')->get($cache_key));

    // Empty the cache and test empty again
    Cache::Static('test')->clear();
    $this->assertEmpty(Cache::Static('test')->get($cache_key));
  }
}
