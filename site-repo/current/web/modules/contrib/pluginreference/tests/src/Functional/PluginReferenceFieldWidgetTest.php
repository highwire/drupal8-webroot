<?php

namespace Drupal\Tests\pluginreference\Functional;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\pluginreference\Traits\PluginReferenceTrait;

/**
 * Test the plugin reference functionality.
 *
 * @group pluginreference
 */
class PluginReferenceFieldWidgetTest extends BrowserTestBase {

  use PluginReferenceTrait;

  /**
   * An admin in user.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * The plugin reference field name.
   *
   * @var string
   */
  protected $fieldName = 'field_pluginreference';

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['node', 'field_ui', 'pluginreference'];

  /**
   * The node type.
   *
   * @var \Drupal\node\Entity\NodeType
   */
  protected $nodeType;

  /**
   * Permissions to grant admin user.
   *
   * @var array
   */
  protected $permissions = [
    'access content',
    'administer content types',
    'administer node fields',
    'administer node form display',
    'bypass node access',
  ];

  /**
   * The plugin type we want to reference.
   *
   * @var string
   */
  protected $targetType = 'field.widget';

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->adminUser = $this->drupalCreateUser($this->permissions);
    $this->nodeType = $this->drupalCreateContentType();
    $this->createPluginReferenceField('node', $this->nodeType->id(), $this->fieldName, $this->targetType);
  }

  /**
   * Test pluginreference select widget with provider grouping.
   */
  public function testPluginReferenceSelectWidget() {
    $assert_session = $this->assertSession();
    EntityFormDisplay::load(sprintf('node.%s.default', $this->nodeType->id()))
      ->setComponent($this->fieldName, [
        'type' => 'plugin_reference_select',
        'settings' => ['provider_grouping' => TRUE],
      ])->save();

    $this->drupalLogin($this->adminUser);

    $this->drupalGet(Url::fromRoute('node.add', ['node_type' => $this->nodeType->id()]));

    $assert_session->fieldExists(sprintf('%s[0][value]', $this->fieldName));
    // Check that grouping per provider is enabled.
    $assert_session->elementExists('css', sprintf('select[name="%s[0][value]"] optgroup', $this->fieldName));
    $title = $this->randomMachineName();
    $edit = [
      'title[0][value]' => $title,
      sprintf('%s[0][value]', $this->fieldName) => 'text_textfield',
    ];
    $this->submitForm($edit, 'Save');
    $node = $this->drupalGetNodeByTitle($title);
    $assert_session->responseContains(new FormattableMarkup('@type %title has been created.', [
      '@type' => $this->nodeType->id(),
      '%title' => $node->toLink($node->label())->toString(),
    ]));
    $this->assertEquals($node->get($this->fieldName)
      ->offsetGet(0)->value, 'text_textfield');

    $this->drupalGet(Url::fromRoute('entity.node.edit_form', ['node' => $node->id()]));
    $assert_session->fieldValueEquals(sprintf('%s[0][value]', $this->fieldName), 'text_textfield');
  }

  /**
   * Test pluginreference select widget without provider grouping.
   */
  public function testPluginReferenceSelectWidgetWithoutProviderGrouping() {
    $assert_session = $this->assertSession();
    EntityFormDisplay::load(sprintf('node.%s.default', $this->nodeType->id()))
      ->setComponent($this->fieldName, [
        'type' => 'plugin_reference_select',
        'settings' => ['provider_grouping' => FALSE],
      ])->save();

    $this->drupalLogin($this->adminUser);

    $this->drupalGet(Url::fromRoute('node.add', ['node_type' => $this->nodeType->id()]));

    $assert_session->fieldExists(sprintf('%s[0][value]', $this->fieldName));
    // Check that grouping per provider is disabled.
    $assert_session->elementNotExists('css', sprintf('select[name="%s[0][value]"] optgroup', $this->fieldName));
    $title = $this->randomMachineName();
    $edit = [
      'title[0][value]' => $title,
      sprintf('%s[0][value]', $this->fieldName) => 'text_textfield',
    ];
    $this->submitForm($edit, 'Save');
    $node = $this->drupalGetNodeByTitle($title);
    $assert_session->responseContains(new FormattableMarkup('@type %title has been created.', [
      '@type' => $this->nodeType->id(),
      '%title' => $node->toLink($node->label())->toString(),
    ]));
    $this->assertEquals($node->get($this->fieldName)
      ->offsetGet(0)->value, 'text_textfield');

    $this->drupalGet(Url::fromRoute('entity.node.edit_form', ['node' => $node->id()]));
    $assert_session->fieldValueEquals(sprintf('%s[0][value]', $this->fieldName), 'text_textfield');
  }

  /**
   * Test pluginreference checkboxes widget.
   */
  public function testPluginReferenceOptionsButtonWidget() {
    $assert_session = $this->assertSession();
    EntityFormDisplay::load(sprintf('node.%s.default', $this->nodeType->id()))
      ->setComponent($this->fieldName, [
        'type' => 'plugin_reference_options_buttons',
      ])->save();

    $this->drupalLogin($this->adminUser);

    $this->drupalGet(Url::fromRoute('node.add', ['node_type' => $this->nodeType->id()]));

    $assert_session->fieldExists($this->fieldName);
    $title = $this->randomMachineName();
    $edit = [
      'title[0][value]' => $title,
      $this->fieldName => 'text_textfield',
    ];
    $this->submitForm($edit, 'Save');
    $node = $this->drupalGetNodeByTitle($title);
    $assert_session->responseContains(new FormattableMarkup('@type %title has been created.', [
      '@type' => $this->nodeType->id(),
      '%title' => $node->toLink($node->label())->toString(),
    ]));
    $this->assertEquals($node->get($this->fieldName)
      ->offsetGet(0)->value, 'text_textfield');

    $this->drupalGet(Url::fromRoute('entity.node.edit_form', ['node' => $node->id()]));
    $assert_session->fieldValueEquals($this->fieldName, 'text_textfield');
  }

  /**
   * Test plugin reference autocomplete widget.
   */
  public function testPluginReferenceAutocompleteWidget() {
    $assert_session = $this->assertSession();
    EntityFormDisplay::load(sprintf('node.%s.default', $this->nodeType->id()))
      ->setComponent($this->fieldName, [
        'type' => 'plugin_reference_autocomplete',
      ])->save();

    $this->drupalGet(Url::fromRoute('pluginreference.plugin_autocomplete', ['target_type' => $this->targetType, 'q' => 'filter']));
    // Make sure a user without the right permissions can access the
    // autocomplete page.
    $assert_session->statusCodeEquals(403);

    $this->drupalLogin($this->adminUser);

    $this->drupalGet(Url::fromRoute('pluginreference.plugin_autocomplete', ['target_type' => $this->targetType, 'q' => 'filter']));
    $assert_session->statusCodeEquals(200);

    $this->drupalGet(Url::fromRoute('node.add', ['node_type' => $this->nodeType->id()]));
    $assert_session->fieldExists($this->fieldName);
    $title = $this->randomMachineName();
    $edit = [
      'title[0][value]' => $title,
      sprintf('%s[0][value]', $this->fieldName) => 'Text field (text_textfield)',
    ];
    $this->submitForm($edit, 'Save');
    $node = $this->drupalGetNodeByTitle($title);
    $assert_session->responseContains(new FormattableMarkup('@type %title has been created.', [
      '@type' => $this->nodeType->id(),
      '%title' => $node->toLink($node->label())->toString(),
    ]));
    $this->assertEquals($node->get($this->fieldName)->offsetGet(0)->value, 'text_textfield');

    $this->drupalGet(Url::fromRoute('entity.node.edit_form', ['node' => $node->id()]));
    $assert_session->fieldValueEquals(sprintf('%s[0][value]', $this->fieldName), 'Text field (text_textfield)');
  }

}
