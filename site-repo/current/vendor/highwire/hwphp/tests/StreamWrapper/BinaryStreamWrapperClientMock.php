<?php

namespace HighWire\test\StreamWrapper;

use HighWire\StreamWrapper\BinaryStreamWrapper;

/**
 * HighWire Binary Stream Wrapper.
 */
class BinaryStreamWrapperClientMock extends BinaryStreamWrapper {

  /**
   * Helper method for testing protected client method.
   */
  public function getClient() {
    return $this->client();
  }

}
