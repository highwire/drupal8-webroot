<?php

namespace HighWire\Exception;

/**
 * Payload not found exception.
 */
class HighWirePayloadNotFoundException extends HighWireException {

  /**
   * Missing payload id.
   *
   * @var string
   */
  protected $missingPayloadId;

  /**
   * Set missing id.
   *
   * @param string $id
   *   The id of the missing payload.
   */
  public function setMissingPayloadId($id) {
    $this->missingPayloadId = $id;
  }

  /**
   * Get the missing payload id.
   *
   * @return string
   *   The missing payload id.
   */
  public function getMissingPayloadId() {
    return $this->missingPayloadId;
  }

}
