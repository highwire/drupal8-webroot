<?php

namespace Drupal\Tests\pluginreference\Traits;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * Provides methods to create a pluginreference field based on default settings.
 *
 * This trait is meant to be used only by test classes.
 */
trait PluginReferenceTrait {

  /**
   * Create a plugin reference field.
   *
   * @param string $entity_type
   *   The entity type to which we want to add the plugin reference.
   * @param string $bundle
   *   The bundle to which we want to add the plugin reference.
   * @param string $field_name
   *   The name we want to give to the field.
   * @param string $target_type
   *   The plugin types we want to reference.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function createPluginReferenceField(string $entity_type, string $bundle, string $field_name, string $target_type): void {
    $field_storage = FieldStorageConfig::create([
      'field_name' => $field_name,
      'entity_type' => $entity_type,
      'type' => 'plugin_reference',
      'settings' => [
        'target_type' => $target_type,
      ],
    ]);
    $field_storage->save();

    FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => $bundle,
    ])->save();
  }

}
