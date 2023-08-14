<?php

namespace Drupal\pluginreference\Plugin\Field\FieldWidget;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'plugin_reference_autocomplete' widget.
 *
 * @FieldWidget(
 *   id = "plugin_reference_autocomplete",
 *   label = @Translation("Autocomplete"),
 *   field_types = {
 *     "plugin_reference"
 *   }
 * )
 */
class PluginReferenceAutocompleteWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * The Plugin Manager.
   *
   * @var \Drupal\Component\Plugin\PluginManagerInterface
   */
  protected $pluginManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    $plugin_id,
    $plugin_definition,
    FieldDefinitionInterface $field_definition,
    array $settings,
    array $third_party_settings,
    PluginManagerInterface $plugin_manager
  ) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->pluginManager = $plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    /** @var \Drupal\field\Entity\FieldConfig $field_definition */
    $field_definition = $configuration['field_definition'];
    $target_type = $field_definition->getFieldStorageDefinition()
      ->getSetting('target_type');

    return new static(
      $plugin_id,
      $plugin_definition,
      $field_definition,
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('plugin.manager.' . $target_type)
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $default_value = NULL;

    if (!empty($items[$delta]->value) && $this->pluginManager->hasDefinition($items[$delta]->value)) {
      $default_value = $this->pluginManager->getDefinition($items[$delta]->value);
    }

    $element['value'] = [
      '#type' => 'plugin_autocomplete',
      '#default_value' => $default_value,
      '#target_type' => $this->getFieldSetting('target_type'),
    ] + $element;

    return $element;
  }

}
