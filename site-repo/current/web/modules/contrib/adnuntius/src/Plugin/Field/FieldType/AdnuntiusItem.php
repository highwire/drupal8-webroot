<?php

namespace Drupal\adnuntius\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the Adnuntius field type.
 *
 * @FieldType(
 *   id = "adnuntius",
 *   label = @Translation("Adnuntius"),
 *   description = @Translation("Show a Adnuntius ad unit as a field."),
 *   default_widget = "adnuntius",
 *   default_formatter = "adnuntius"
 * )
 */
class AdnuntiusItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['auid'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Adnuntius Ad Unit Id'));
    $properties['invocation_method'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Banner invocation method.'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'auid' => [
          'description' => 'Adnuntius Ad Unit Id',
          'type' => 'varchar',
          'default' => NULL,
          'length' => 255,
        ],
        'invocation_method' => [
          'description' => 'Banner invocation method.',
          'type' => 'varchar',
          'default' => NULL,
          'length' => 255,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return ['invocation_method_per_entity' => FALSE] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element['invocation_method_per_entity'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Select invocation method per entity.'),
      '#default_value' => $this->getSetting('invocation_method_per_entity'),
      '#description' => $this->t('You will be able to select the invocation method for each entity.'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $isEmpty =
      empty($this->get('auid')->getValue()) &&
      empty($this->get('invocation_method')->getValue());

    return $isEmpty;
  }

}
