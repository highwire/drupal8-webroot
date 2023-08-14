<?php

namespace Drupal\Tests\adnuntius\FunctionalJavascript;

use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\adnuntius\Traits\AdUnitTrait;

/**
 * Tests the Adnuntius module.
 *
 * @group adnuntius
 */
class AdnuntiusBlockJavascriptTest extends WebDriverTestBase {

  use AdUnitTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'block',
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

    // Log in as a user, that can add and configure blocks.
    $this->user = $this->drupalCreateUser([
      'administer adnuntius',
      'administer blocks',
    ]);
    $this->drupalLogin($this->user);
  }

  /**
   * Test adding an adnuntius block creates the expected markup.
   */
  public function testAddAdnuntiusBlock() {
    // Add an ad unit.
    $ad_unit = $this->addAdUnit();

    // Add a adnuntius block using the iframe method.
    $edit = [
      'settings[auid]' => $ad_unit['auid'],
      'settings[invocation_method]' => 'iframe',
      'region' => 'content',
    ];
    $this->drupalPostForm('admin/structure/block/add/adnuntius_block', $edit, 'Save block');

    // Check that the ad wrappers and required properties got loaded.
    // We do not use a real "auid", so we check only for the existence of the
    // right properties.
    $this->drupalGet('<front>');
    $this->assertSession()->elementExists('css', '#adn-' . $ad_unit['auid']);
    $this->assertSession()->elementExists('css', '#adn-' . $ad_unit['auid'] . ' + script + script');
    $this->assertSession()->elementContains('css', '#adn-' . $ad_unit['auid'] . ' + script + script', 'auId: \'' . $ad_unit['auid'] . '\'');
    $this->assertSession()->elementContains('css', '#adn-' . $ad_unit['auid'] . ' + script + script', 'auW: ' . $ad_unit['width'] . '');
    $this->assertSession()->elementContains('css', '#adn-' . $ad_unit['auid'] . ' + script + script', 'auH: ' . $ad_unit['height'] . '');
    $this->assertSession()->elementContains('css', '#adn-' . $ad_unit['auid'] . ' + script + script', 'container: \'iframe\'');

    // Change invocation method to "div" and check the markup.
    $edit = [
      'settings[invocation_method]' => 'div',
    ];
    $this->drupalPostForm('admin/structure/block/manage/adnuntiusblock', $edit, 'Save block');
    $this->assertSession()->elementContains('css', '#adn-' . $ad_unit['auid'] . ' + script + script', 'container: \'div\'');
  }

}
