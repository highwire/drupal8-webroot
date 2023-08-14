<?php

namespace Drupal\Tests\adnuntius\Traits;

/**
 * Provide a unified handling for ad units.
 */
trait AdUnitTrait {

  /**
   * Ad a new ad unit.
   *
   * @param array $ad_unit
   *   An add unit array.
   */
  public function addAdUnit($ad_unit = []) {
    // Add a new ad unit.
    $ad_unit = $ad_unit + [
      'label' => 'Topbanner',
      'auid' => '100000000008c82f2',
      'width' => 1080,
      'height' => 300,
      'weight' => 50,
    ];
    $edit = [
      'ad_units[new][label]' => $ad_unit['label'],
      'ad_units[new][auid]' => $ad_unit['auid'],
      'ad_units[new][width]' => $ad_unit['width'],
      'ad_units[new][height]' => $ad_unit['height'],
      'ad_units[new][weight]' => $ad_unit['weight'],
    ];
    $this->drupalPostForm('admin/config/services/adnuntius', $edit, 'Save configuration');

    return $ad_unit;
  }

}
