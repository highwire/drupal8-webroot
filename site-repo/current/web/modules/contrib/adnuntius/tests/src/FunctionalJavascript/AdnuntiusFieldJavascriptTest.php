<?php

namespace Drupal\Tests\adnuntius\FunctionalJavascript;

use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\adnuntius\Traits\AdUnitTrait;

/**
 * Tests the Adnuntius module.
 *
 * @group adnuntius
 */
class AdnuntiusFieldJavascriptTest extends WebDriverTestBase {

  use AdUnitTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'field_ui',
    'node',
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

    $this->drupalCreateContentType(['type' => 'article']);
    $this->setupFields();

    // Log in as a user, that can add and configure blocks.
    $this->user = $this->drupalCreateUser([
      'create article content',
      'edit any article content',
      'administer nodes',
      'administer node fields',
      'administer node display',
      'administer node form display',
      'administer adnuntius',
      'use adnuntius field',
    ]);
    $this->drupalLogin($this->user);
  }

  /**
   * Test adding an adnuntius ad via a field.
   */
  public function testAddAdnuntiusField() {
    // Add a new ad unit.
    $ad_unit = $this->addAdUnit();

    // Test the adnuntius field with default settings.
    $this->drupalGet('node/add/article');
    $edit = [
      'title[0][value]' => 'My test title',
      'field_adnuntius[0][auid]' => $ad_unit['auid'],
    ];
    $this->drupalPostForm('node/add/article', $edit, 'Save');

    // Verify that the ad is displayed correctly. By default with the "iframe"
    // method.
    $this->assertSession()->elementExists('css', '#adn-' . $ad_unit['auid']);
    $this->assertSession()->elementExists('css', '#adn-' . $ad_unit['auid'] . ' + script + script');
    $this->assertSession()->elementContains('css', '#adn-' . $ad_unit['auid'] . ' + script + script', 'auId: \'' . $ad_unit['auid'] . '\'');
    $this->assertSession()->elementContains('css', '#adn-' . $ad_unit['auid'] . ' + script + script', 'auW: ' . $ad_unit['width'] . '');
    $this->assertSession()->elementContains('css', '#adn-' . $ad_unit['auid'] . ' + script + script', 'auH: ' . $ad_unit['height'] . '');
    $this->assertSession()->elementContains('css', '#adn-' . $ad_unit['auid'] . ' + script + script', 'container: \'iframe\'');

    // Change invocation method to "div".
    $this->drupalGet('admin/structure/types/manage/article/display');
    $this->click('[name="field_adnuntius_settings_edit"]');
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->getSession()->getPage()->fillField('fields[field_adnuntius][settings_edit_form][settings][invocation_method]', 'div');
    $this->getSession()->getPage()->findButton('Update')->click();
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->getSession()->getPage()->findButton('Save')->click();

    // Verify that the rendered markup uses the "div" invocation method.
    $this->drupalGet('node/1');
    $this->assertSession()->elementContains('css', '#adn-' . $ad_unit['auid'] . ' + script + script', 'container: \'div\'');
  }

  /**
   * Tests that only the whitelisted ad units available when being configured.
   */
  public function testWhitelistingAdUnits() {
    // Add a new ad unit.
    $ad_unit = $this->addAdUnit();

    // Add a secondary ad unit.
    $secondary_ad_unit = $this->addAdUnit([
      'label' => 'Skyscraper',
      'auid' => '200000000009s4s33',
      'width' => 120,
      'height' => 900,
    ]);

    // Whitelist a single ad unit.
    $this->drupalGet('admin/structure/types/manage/article/form-display');
    $this->click('[name="field_adnuntius_settings_edit"]');
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->getSession()->getPage()->selectFieldOption('fields[field_adnuntius][settings_edit_form][settings][enabled_ad_units][]', $secondary_ad_unit['auid']);
    $this->getSession()->getPage()->findButton('Update')->click();
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->getSession()->getPage()->findButton('Save')->click();

    // Add a node and check, that only the whitelisted ad unit is available.
    $this->drupalGet('node/add/article');
    $this->assertSession()->elementsCount('css', '[name="field_adnuntius[0][auid]"] option', 1);
    $this->assertSession()->optionExists('field_adnuntius[0][auid]', $secondary_ad_unit['auid']);

    // Whitelist all ad units.
    $this->drupalGet('admin/structure/types/manage/article/form-display');
    $this->click('[name="field_adnuntius_settings_edit"]');
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->getSession()->getPage()->selectFieldOption('fields[field_adnuntius][settings_edit_form][settings][enabled_ad_units][]', $ad_unit['auid'], TRUE);
    $this->getSession()->getPage()->selectFieldOption('fields[field_adnuntius][settings_edit_form][settings][enabled_ad_units][]', $secondary_ad_unit['auid'], TRUE);
    $this->getSession()->getPage()->findButton('Update')->click();
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->getSession()->getPage()->findButton('Save')->click();

    // Add a node and check, that all ad units are available.
    $this->drupalGet('node/add/article');
    $this->assertSession()->elementsCount('css', '[name="field_adnuntius[0][auid]"] option', 2);
    $this->assertSession()->optionExists('field_adnuntius[0][auid]', $ad_unit['auid']);
    $this->assertSession()->optionExists('field_adnuntius[0][auid]', $secondary_ad_unit['auid']);
  }

  /**
   * Test that the invocation method per entity option works as expected.
   */
  public function testInvocationMethodPerEntityOption() {
    // Add a new ad unit.
    $ad_unit = $this->addAdUnit();

    // Enable the invocation method per entity option.
    $this->drupalGet('admin/structure/types/manage/article/fields/node.article.field_adnuntius');
    $this->getSession()->getPage()->selectFieldOption('default_value_input[field_adnuntius][0][auid]', $ad_unit['auid']);
    $this->getSession()->getPage()->checkField('settings[invocation_method_per_entity]');
    $this->getSession()->getPage()->findButton('Save settings')->click();

    $this->drupalGet('admin/structure/types/manage/article/fields/node.article.field_adnuntius');

    // Add a node and check, that the invocation method can be configured.
    $this->drupalGet('node/add/article');
    $this->getSession()->getPage()->fillField('title[0][value]', 'My test title');
    $this->assertSession()->elementExists('css', '[name="field_adnuntius[0][invocation_method]"]');
    $this->getSession()->getPage()->selectFieldOption('field_adnuntius[0][invocation_method]', 'div');
    $this->getSession()->getPage()->findButton('Save')->click();

    // Check that the selected invocation method was used.
    $this->assertSession()->elementExists('css', '#adn-' . $ad_unit['auid']);
    $this->assertSession()->elementExists('css', '#adn-' . $ad_unit['auid'] . ' + script + script');
    $this->assertSession()->elementContains('css', '#adn-' . $ad_unit['auid'] . ' + script + script', 'container: \'div\'');
  }

  /**
   * Creates the necessary fields.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function setupFields() {
    FieldStorageConfig::create([
      'field_name' => 'field_adnuntius',
      'entity_type' => 'node',
      'type' => 'adnuntius',
      'cardinality' => 1,
    ])->save();
    FieldConfig::create([
      'entity_type' => 'node',
      'field_name' => 'field_adnuntius',
      'bundle' => 'article',
      'label' => 'Adnuntius',
    ])->save();
    EntityFormDisplay::load('node.article.default')
      ->setComponent('field_adnuntius')
      ->save();
    EntityViewDisplay::load('node.article.default')
      ->setComponent('field_adnuntius')
      ->save();
  }

}
