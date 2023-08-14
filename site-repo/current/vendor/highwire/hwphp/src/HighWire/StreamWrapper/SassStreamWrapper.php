<?php

namespace HighWire\StreamWrapper;

use HighWire\Clients\ClientFactory;
use HighWire\Clients\Sass\Sass;

/**
 * HighWire Sass Stream Wrapper.
 */
class SassStreamWrapper extends StreamWrapperBase implements StreamWrapperInterface {

  /**
   * {@inheritdoc}
   */
  const SCHEME = 'sass';

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'HighWire Sass stream wrapper';
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return 'HighWire Sass stream wrapper';
  }

  /**
   * {@inheritdoc}
   * 
   * @return \HighWire\Clients\Sass\Sass
   *   The sass client.
   */
  protected function client() {
    if (!empty($this->client)) {
      return $this->client;
    }
    else {
      $this->client = ClientFactory::get('sass');
      return $this->client;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getResponse($uri) {
    $apath = Sass::parseURI($uri);
    return $this->client()->getResource($apath);
  }

  /**
   * {@inheritdoc}
   */
  protected function headResponse($uri) {
    $apath = Sass::parseURI($uri);
    return $this->client()->headResource($apath);
  }

  /**
   * {@inheritdoc}
   */
  public function getExternalUrl() {
    $baseuri = $this->client()->getGuzzleConfig('base_uri');
    $apath = Sass::parseURI($this->uri);
    return rtrim($baseuri, '/') . $apath;
  }

}
