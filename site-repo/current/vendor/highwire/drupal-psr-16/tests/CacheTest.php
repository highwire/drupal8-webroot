<?php
 
use HighWire\DrupalPSR16\Cache;

class CacheTest extends \PHPUnit_Framework_TestCase {

  public function testCache() {

    $memoryCache = new \Drupal\Core\Cache\MemoryBackend('test');
    $cache = new Cache($memoryCache);

    // Test Single
    // -------------

    $key = 'cachekey';
    $value = 'cachevalue';

    // Should be empty before anything is set
    $this->assertEmpty($cache->get($key));

    // Deafult value on empty cache
    $this->assertEquals($cache->get($key, 'default'), 'default');

    // Set and get a value
    $cache->set($key, $value);
    $this->assertEquals($cache->get($key), $value);

    // Clear the cache
    $cache->clear();
    $this->assertEmpty($cache->get($key));

    // Test Multiple
    // -------------
    $items = array('key0' => 'value0', 'key1' => 'value1');

    // Empty   
    $this->assertEquals($cache->getMultiple(array_keys($items)), array('key0' => NULL, 'key1' => NULL));

    // Empty with default
    $this->assertEquals($cache->getMultiple(array_keys($items), 'default'), array('key0' => 'default', 'key1' => 'default'));

    // Test set and get
    $cache->setMultiple($items);
    $this->assertEquals($cache->getMultiple(array_keys($items)), array('key0' => 'value0', 'key1' => 'value1'));


  }

}