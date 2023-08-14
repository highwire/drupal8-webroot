<?php

namespace Drupal\Tests\pluginreference\Kernel;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\entity_test\Entity\EntityTest;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\pluginreference\Traits\PluginReferenceTrait;

/**
 * Test the pluginreference formatter functionality.
 *
 * @group pluginreference
 */
class PluginReferenceFieldFormatterTest extends KernelTestBase {

  use PluginReferenceTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'field',
    'text',
    'entity_test',
    'user',
    'system',
    'pluginreference',
  ];

  /**
   * The entity type.
   *
   * @var string
   */
  protected $entityType;

  /**
   * The entity bundle.
   *
   * @var string
   */
  protected $bundle;

  /**
   * The field name.
   *
   * @var string
   */
  protected $fieldName;

  /**
   * The entity view display definition.
   *
   * @var \Drupal\Core\Entity\Display\EntityViewDisplayInterface
   */
  protected $display;

  /**
   * The plugin type we want to reference.
   *
   * @var string
   */
  protected $targetType;

  /**
   * The entity to be tested.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->installConfig(['field']);
    $this->installEntitySchema('entity_test');

    $this->entityType = 'entity_test';
    $this->bundle = $this->entityType;
    $this->fieldName = mb_strtolower($this->randomMachineName());
    $this->targetType = 'field.widget';

    $this->createPluginReferenceField($this->entityType, $this->bundle, $this->fieldName, $this->targetType);

    $this->entity = EntityTest::create([]);
    $this->entity->{$this->fieldName}->value = 'text_textfield';
    $this->display = \Drupal::service('entity_display.repository')
      ->getViewDisplay($this->entityType, $this->bundle);
  }

  /**
   * Renders fields of a given entity with a given display.
   *
   * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
   *   The entity object with attached fields to render.
   * @param \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display
   *   The display to render the fields in.
   *
   * @return string
   *   The rendered entity fields.
   */
  protected function renderEntityFields(FieldableEntityInterface $entity, EntityViewDisplayInterface $display) {
    $content = $display->build($entity);
    $content = $this->render($content);
    return $content;
  }

  /**
   * Test pluginreference ID formatter.
   */
  public function testPluginReferenceIdFormatter() {
    $this->display->setComponent($this->fieldName, [
      'type' => 'plugin_reference_id',
      'settings' => [],
    ]);
    $this->display->save();

    $this->renderEntityFields($this->entity, $this->display);
    $this->assertRaw('text_textfield');
  }

  /**
   * Test pluginreference label formatter.
   */
  public function testPluginReferenceLabelFormatter() {
    $this->display->setComponent($this->fieldName, [
      'type' => 'plugin_reference_label',
      'settings' => [],
    ]);
    $this->display->save();

    $this->renderEntityFields($this->entity, $this->display);
    $this->assertRaw('Text field');
  }

}
