<?php

namespace HighWire\test\StreamWrapper;

use HighWire\StreamWrapper\SassStreamWrapper;

/**
 * HighWire Sass Stream Wrapper.
 */
class SassStreamWrapperClientMock extends SassStreamWrapper {
  /**
   * Helper method for testing protected client method.
   */
  public function getClient() {
    return $this->client();
  }

}
