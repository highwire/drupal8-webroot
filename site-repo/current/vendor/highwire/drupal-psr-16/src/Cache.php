<?php

namespace HighWire\DrupalPSR16;

class Cache implements \Psr\SimpleCache\CacheInterface {

  /**
    * @var \Drupal\Core\Cache\CacheBackendInterface
    */
  protected $drupal_cache;

  /**
    * @param \Drupal\Core\Cache\CacheBackendInterface $drupal_cache
    *   The drupal cache backend to convert to PSR-16
    * 
    * @example
    *   $drupalcache = \Drupal::cache('mybin');
    *   $psr16cache = new \HighWire\DrupalPSR16\Cache($drupalcache);
    */
  public function __construct(\Drupal\Core\Cache\CacheBackendInterface $drupal_cache) {
    $this->drupal_cache = $drupal_cache;
  }

  /**
    * {@inheritdoc}
    */
  public function get($key, $default = null) {
    $cache = $this->drupal_cache->get($key);
    if (empty($cache)) {
      return $default;
    }
    else {
      return $cache->data;
    }
  }

  /**
    * {@inheritdoc}
    */
  public function set($key, $value, $ttl = NULL) {
    if ($ttl === NULL) {
      $this->drupal_cache->set($key, $value);
    }
    else {
      $this->drupal_cache->set($key, $value, time() + $ttl);
    }
    return true;
  }

  /**
    * {@inheritdoc}
    */
  public function delete($key) {
    $this->drupal_cache->delete($key);
    return true;
  }

  /**
    * {@inheritdoc}
    */
  public function clear() {
    $this->drupal_cache->deleteAll();
    return true;
  }

  /**
    * {@inheritdoc}
    */
  public function getMultiple($keys, $default = null) {
    $result = array();
    $k = $keys;
    $caches = $this->drupal_cache->getMultiple($k);
    foreach ($keys as $key) {
      if (!empty($caches[$key])) {
        $result[$key] = $caches[$key]->data;
      }
      else {
        $result[$key] = $default;
      }
    }
    return $result;
  }

  /**
    * {@inheritdoc}
    */
  public function setMultiple($values, $ttl = null) {
    $items = array();
    foreach ($values as $key => $value) {
      if ($ttl === NULL) {
        $items[$key] = array('data' => $value);
      }
      else {
        $items[$key] = array('data' => $value, 'expire' => time() + $ttl);
      }
    }
    $this->drupal_cache->setMultiple($items);
    return true;
  }

  /**
    * {@inheritdoc}
    */
  public function deleteMultiple($keys) {
    $this->drupal_cache->deleteMultiple($keys);
    return true;
  }

  /**
    * {@inheritdoc}
    */
  public function has($key) {
    $cache = $this->drupal_cache->get($key);
    return !empty($cache);
  }

}