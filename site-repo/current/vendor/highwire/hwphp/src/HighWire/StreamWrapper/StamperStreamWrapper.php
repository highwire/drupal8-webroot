<?php

namespace HighWire\StreamWrapper;

use HighWire\Clients\ClientFactory;
use HighWire\Clients\PDFStamper\PDFStamper;

/**
 * HighWire PDFStamper Stream Wrapper.
 */
class StamperStreamWrapper extends StreamWrapperBase implements StreamWrapperInterface {

  /**
   * {@inheritdoc}
   */
  const SCHEME = 'stamper';

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'HighWire PDFStamper stream wrapper';
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return 'HighWire PDFStamper stream wrapper';
  }

  /**
   * {@inheritdoc}
   *
   * @return \HighWire\Clients\PDFStamper\PDFStamper
   *   The PDFStamper client.
   */
  protected function client() {
    if (!empty($this->client)) {
      return $this->client;
    }
    else {
      $this->client = ClientFactory::get('pdf-stamper');
      return $this->client;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getResponse($uri) {
    $apath = PDFStamper::parseURI($uri);
    return $this->client()->getResource($apath);
  }

  /**
   * {@inheritdoc}
   */
  protected function headResponse($uri) {
    $apath = PDFStamper::parseURI($uri);
    return $this->client()->headResource($apath);
  }

  /**
   * {@inheritdoc}
   */
  public function getExternalUrl() {
    $baseuri = $this->client()->getGuzzleConfig('base_uri');
    $apath = PDFStamper::parseURI($this->uri);
    return rtrim($baseuri, '/') . $apath;
  }

}
