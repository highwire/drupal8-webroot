<?php

namespace Drupal\adnuntius;

/**
 * Interface AdnuntiusManagerInterface.
 */
interface AdnuntiusManagerInterface {

  /**
   * Get all available ad units in the system.
   *
   * @return array]null
   *   The ad unit list NULL if empty.
   */
  public function getAdUnits();

  /**
   * Returns an Ad Unit by auID.
   *
   * @param string $auId
   *   The Ad Unit Id to be requested.
   *
   * @return array|null
   *   The ad unit or NULL if empty.
   */
  public function getAdUnit($auId);

  /**
   * Returns the ad units as an option list.
   *
   * @return array
   *   The ad units as key/value pair.
   */
  public function getAdUnitsOptionList();

  /**
   * Returns the invocation method options.
   *
   * @return array
   *   The invocation methods as a key/value pair.
   */
  public function getInvocationMethodOptionList();

  /**
   * Renders the ad region.
   *
   * @param string $auId
   *   The adnuntius id.
   * @param string $invocation_method
   *   The invocation method.
   *
   * @return array
   *   A render array to render the ad.
   */
  public function render($auId, $invocation_method);

}
