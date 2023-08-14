<?php

namespace Drupal\adnuntius\Plugin\Field\FieldWidget;

use Drupal\adnuntius\AdnuntiusManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the Adnuntius widget.
 *
 * @FieldWidget(
 *   id = "adnuntius",
 *   label = @Translation("Default"),
 *   field_types = {
 *     "adnuntius"
 *   }
 * )
 */
class AdnuntiusWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The adnuntius manager.
   *
   * @var \Drupal\adnuntius\AdnuntiusManagerInterface
   */
  protected $adnuntiusManager;

  /**
   * Constructs a new AdnuntiusWidget.
   *
   * @param string $plugin_id
   *   The plugin_id for the widget.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the widget is associated.
   * @param array $settings
   *   The widget settings.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\adnuntius\AdnuntiusManagerInterface $adnuntius_manager
   *   The adnuntius manager.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, ConfigFactoryInterface $config_factory, AccountInterface $current_user, AdnuntiusManagerInterface $adnuntius_manager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, []);
    $this->configFactory = $config_factory;
    $this->currentUser = $current_user;
    $this->adnuntiusManager = $adnuntius_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $container->get('config.factory'),
      $container->get('current_user'),
      $container->get('adnuntius.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $ad_units = $this->adnuntiusManager->getAdUnitsOptionList();
    $enabled_ad_units = $this->getSetting('enabled_ad_units');
    // Only filter, if there were enabled ad units specified.
    if (!empty($enabled_ad_units)) {
      foreach ($ad_units as $key => $zone) {
        if (!in_array($key, $enabled_ad_units)) {
          unset($ad_units[$key]);
        }
      }
    }

    $element['#theme_wrappers'][] = 'fieldset';
    $element['auid'] = [
      '#type' => 'textfield',
      '#title' => t('Ad unit'),
      '#description' => t('The Adnuntius Ad Unit Id.'),
      '#default_value' => isset($items->auid) ? $items->auid : NULL,
      '#access' => $this->currentUser->hasPermission('use adnuntius field'),
      '#required' => $this->fieldDefinition->isRequired(),
    ];
    // If ad units are available, transform number field into a select field.
    if (!empty($ad_units)) {
      $element['auid']['#type'] = 'select';
      $element['auid']['#options'] = $ad_units;
    }

    if ($this->getFieldSetting('invocation_method_per_entity')) {
      $invocation_methods = $this->adnuntiusManager->getInvocationMethodOptionList();
      $methods = $this->getSetting('invocation_methods');
      // Filter the invocation methods, by the whitelisted ones in the field
      // widget. Only filter, if there was a whitelist specified.
      if (!empty($methods)) {
        foreach ($invocation_methods as $key => $method) {
          if (!in_array($key, $methods)) {
            unset($invocation_methods[$key]);
          }
        }
      }
      $element['invocation_method'] = [
        '#type' => 'select',
        '#title' => $this->t('Invocation method'),
        '#description' => $this->t('Banner invocation method. How will the ads be displayed.'),
        '#default_value' => isset($items->invocation_method) ? $items->invocation_method : '',
        '#access' => $this->currentUser->hasPermission('use adnuntius field'),
        '#options' => $invocation_methods,
        '#required' => $this->fieldDefinition->isRequired(),
      ];
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'enabled_ad_units' => [],
      'invocation_methods' => [],
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = [];

    // Add whitelist options, to allow only specific zones to be selectable
    // in the entity form.
    $element['enabled_ad_units'] = [
      '#type' => 'select',
      '#title' => $this->t('Enabled Ad Units'),
      '#description' => $this->t('Whitelist ad units, that will be able to select in the entity form. Otherwise all ad units will be selectable.'),
      '#default_value' => $this->getSetting('enabled_ad_units'),
      '#options' => $this->adnuntiusManager->getAdUnitsOptionList(),
      '#multiple' => TRUE,
    ];

    // Add whitelist options only, when they are allowed to be specified per
    // entity.
    if ($this->getFieldSetting('invocation_method_per_entity')) {
      $element['invocation_methods'] = [
        '#type' => 'select',
        '#title' => $this->t('Invocation methods'),
        '#description' => $this->t('Whitelist invocation methods, that will be possible to select in the entity form.'),
        '#default_value' => $this->getSetting('invocation_methods'),
        '#options' => $this->adnuntiusManager->getInvocationMethodOptionList(),
        '#multiple' => TRUE,
      ];
    }
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    // Show the enabled zones summary.
    if (!empty($this->getSetting('enabled_ad_units'))) {
      $this->getSetting('enabled_ad_units');
      $ad_units_options = $this->adnuntiusManager->getAdUnitsOptionList();
      $ad_units = [];
      foreach ($this->getSetting('enabled_ad_units') as $zone_id) {
        $ad_units[] = $ad_units_options[$zone_id];
      }
      $summary[] = $this->t('Selectable ad units: @ad_units', ['@ad_units' => implode(', ', $ad_units)]);
    }
    else {
      $summary[] = $this->t('All ad units are selectable.');
    }

    // Show the invocation method options summary.
    if ($this->getFieldSetting('invocation_method_per_entity')) {
      // Use invocation method label.
      $invocation_method_options = $this->adnuntiusManager->getInvocationMethodOptionList();
      $methods = $this->getSetting('invocation_methods');
      $invocation_methods = [];
      foreach ($methods as $method) {
        $invocation_methods[] = $invocation_method_options[$method];
      }

      $summary[] = $this->t('Available methods: @invocation_methods', ['@invocation_methods' => implode(', ', $invocation_methods)]);
    }
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $key => $value) {
      if (empty($value['auid'])) {
        unset($values[$key]['auid']);
      }
      if (empty($value['invocation_method'])) {
        unset($values[$key]['invocation_method']);
      }
    }

    return $values;
  }

}
