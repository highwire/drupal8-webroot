<?php

namespace Drupal\pluginreference\Plugin\Field\FieldWidget;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'plugin_reference_select' widget.
 *
 * @FieldWidget(
 *   id = "plugin_reference_select",
 *   label = @Translation("Select list"),
 *   field_types = {
 *     "plugin_reference"
 *   }
 * )
 */
class PluginReferenceSelectWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

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
    ModuleHandlerInterface $module_handler,
    PluginManagerInterface $plugin_manager
  ) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);

    $this->moduleHandler = $module_handler;
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
      $container->get('module_handler'),
      $container->get('plugin.manager.' . $target_type)
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $options = [];

    if (!$element['#required']) {
      $options[''] = $this->t('- None -');
    }

    foreach ($this->pluginManager->getDefinitions() as $plugin_type_id => $plugin_definition) {
      if ((bool) $this->getSetting('provider_grouping') === TRUE) {
        $provider = $plugin_definition['provider'] === 'core' ? 'system' : $plugin_definition['provider'];
        $options[$this->moduleHandler->getName($provider)][$plugin_type_id] = $plugin_definition['label'] ?? $plugin_type_id;
      }
      else {
        $options[$plugin_type_id] = $plugin_definition['label'] ?? $plugin_type_id;
      }
    }

    $element['value'] = [
      '#type' => 'select',
      '#default_value' => $items[$delta]->value ?? NULL,
      '#options' => $options,
    ] + $element;

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'provider_grouping' => TRUE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = [];

    $elements['provider_grouping'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Group per provider'),
      '#description' => $this->t('Group the options per provider.'),
      '#default_value' => $this->getSetting('provider_grouping'),
    ];

    return $elements;
  }

}
