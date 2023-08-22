<?php

namespace HighWire\StreamWrapper;

use HighWire\Clients\ClientFactory;
use HighWire\Clients\Binary\Binary;

/**
 * HighWire Binary Stream Wrapper.
 */
class BinaryStreamWrapper extends StreamWrapperBase implements StreamWrapperInterface {

  /**
   * {@inheritdoc}
   */
  const SCHEME = 'binary';

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'HighWire Binary stream wrapper';
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return 'HighWire Binary stream wrapper';
  }

  /**
   * {@inheritdoc}
   * 
   * @return \HighWire\Clients\Binary\Binary
   *   The binary client.
   */
  protected function client() {
    if (!empty($this->client)) {
      return $this->client;
    }
    else {
      $this->client = ClientFactory::get('binary');
      return $this->client;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getResponse($uri) {
    $binary_id = Binary::binaryIdFromURI($uri);
    return $this->client()->getBinary($binary_id);
  }

  /**
   * {@inheritdoc}
   */
  protected function headResponse($uri) {
    $binary_id = Binary::binaryIdFromURI($uri);
    return $this->client()->headBinary($binary_id);
  }

  /**
   * {@inheritdoc}
   */
  public function getExternalUrl() {
    $baseuri = $this->client()->getGuzzleConfig('base_uri');
    $binary = Binary::parseURI($this->uri);
    return $baseuri . 'binary/' . $binary['ingest_key'] . '/' . $binary['binary_hash'] . (isset($binary['filename']) ? '/' . $binary['filename'] : '');
  }

}
