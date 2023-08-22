<?php

namespace HighWire\Parser\AC;

/**
 * Access Control Privilege.
 *
 * @see Response
 *
 * Example XML:
 * @code
 * <ac:privilege runtime-id="5e47762d-1532-4b8d-b612-8a5166a8b51c" type="subscription" user-id="1911" status="ACTIVE" expiration="9999-12-31T23:59:59.999-08:00" privilege-set="MALL" resource="urn:doi:10.1534/genetics.116.197491" />
 * @endcode
 */
class Privilege extends ACElement {

  /**
   * {@inheritdoc}
   */
  protected $id_attribute = 'id';

  /**
   * This is overriden to support AC 2 and 3
   *
   * {@inheritdoc}
   */
  public function getId() {

    if (empty($this->id_attribute)) {
      throw new \Exception("Cannot set id on ACElement where no id attribute has been specified");
    }
    // AC 3
    $id = $this->getAttribute($this->id_attribute);
    if (empty($id)) {
      $id = $this->getAttribute('runtime-id');
    }

    return $id;
  }

  /**
   * Get the privilege type.
   *
   * @return string
   *   Examples: "subscription", "pay-per-view"
   */
  public function getType() {
    return $this->getAttribute('type');
  }

  /**
   * Get the user-id that owns this privilege.
   *
   * @return string
   *   The user id for the privilege.
   */
  public function getUserId() {
    return $this->getAttribute('user-id');
  }

  /**
   * Get the status of this privilege.
   *
   * @return string
   *   Examples: "ACTIVE", "EXPIRED"
   */
  public function getStatus() {
    return $this->getAttribute('status');
  }

  /**
   * Get the expiration date for this privilege.
   *
   * @return string
   *   ISO-8601 date string
   */
  public function getExpiration() {
    return $this->getAttribute('expiration');
  }

  /**
   * Get the privilege set for this privilege.
   *
   * @return string
   *   The priviege set.
   */
  public function getPrivilegeSet() {
    return $this->getAttribute('privilege-set');
  }

  /**
   * Get the resource for this privilege.
   *
   * @return string
   *   The resource as defined by the 'resource' attribute set.
   */
  public function getResource() {
    return $this->getAttribute('resource');
  }

  /**
   * Get the resource for this privilege.
   *
   * @return string|null
   *   The license as defined by the 'license' attribute set.
   */
  public function getLicenseId() {
    $license_raw = $this->getLicense();

    if (!empty($license_raw)) {
      $license_parts = explode(':', $license_raw);
      return $license_parts[1];
    }

    return NULL;
  }

  /**
   * Get the license for this privilege.
   *
   * @return string
   *   The license as defined by the 'license' attribute set.
   */
  public function getLicense() {
    return $this->getAttribute('license');
  }

  /**
   * Check to see if this privilege is active.
   *
   * @return bool
   *   TRUE if the privilege is active, FALSE if the privilege is inactive.
   */
  public function isActive() {
    $status = $this->getStatus();
    // AC 2 status 'ACTIVE' vs AV 3 status 'active'
    return empty($status) || strtolower($status) == 'active';
  }

  /**
   * Check to see if this privilege is a guest privilege open to all.
   *
   * @return bool
   *   TRUE if the privilege is a guest privilege, FALSE otherwise.
   */
  public function isGuest() {
    return ($this->getId() == 'urn:ac.highwire.org:guest:privilege');
  }

}
