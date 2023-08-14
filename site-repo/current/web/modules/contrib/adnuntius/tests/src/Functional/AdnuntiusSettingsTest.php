<?php

namespace Drupal\Tests\adnuntius\Functional;

use Drupal\Tests\adnuntius\Traits\AdUnitTrait;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests the adnuntius configuration.
 *
 * @group adnuntius
 */
class AdnuntiusSettingsTest extends BrowserTestBase {

  use AdUnitTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'adnuntius',
  ];

  /**
   * A user with permissions to access the adnuntius settings page.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Log in as a user, that can configure the adnuntius settings.
    $this->user = $this->drupalCreateUser([
      'administer adnuntius',
      'administer site configuration',
    ]);
    $this->drupalLogin($this->user);
  }

  /**
   * Tests configuring ad units.
   */
  public function testSettingsPage() {
    $this->drupalGet('admin/config/services/adnuntius');

    // Check that we have tabledrag handles.
    $this->assertSession()->elementExists('css', 'table#edit-ad-units .draggable');

    // Check that we initially see only the new ad unit row.
    $this->assertSession()->elementsCount('css', 'table#edit-ad-units tbody tr', 1);
    $this->assertSession()->elementExists('css', '[name="ad_units[new][label]"]');

    // Add a new ad unit.
    $ad_unit = $this->addAdUnit();

    // Check that we see 2 rows now. The newly created ad unit and the new
    // ad unit.
    $this->assertSession()->elementsCount('css', 'table#edit-ad-units tbody tr', 2);
    $this->assertSession()->elementExists('css', '[name="ad_units[' . $ad_unit['auid'] . '][auid]"]');
    $this->assertSession()->elementExists('css', '[name="ad_units[new][label]"]');

    // Check, that the config also has the correct values.
    $ad_units = $config = $this->config('adnuntius.settings')->get('ad_units');
    $this->assertEquals($ad_unit, $ad_units[$ad_unit['auid']]);

    // Test that emptying values removes the row.
    $edit = [
      'ad_units[' . $ad_unit['auid'] . '][label]' => '',
      'ad_units[' . $ad_unit['auid'] . '][auid]' => '',
      'ad_units[' . $ad_unit['auid'] . '][width]' => '',
      'ad_units[' . $ad_unit['auid'] . '][height]' => '',
    ];
    $this->drupalGet('admin/config/services/adnuntius');
    $this->drupalPostForm('admin/config/services/adnuntius', $edit, 'Save configuration');
    $this->assertSession()->elementsCount('css', 'table#edit-ad-units tbody tr', 1);
    $this->assertSession()->elementExists('css', '[name="ad_units[new][label]"]');
  }

}
