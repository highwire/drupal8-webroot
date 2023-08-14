<?php

namespace Drupal\adnuntius\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure adnuntius settings for this site.
 */
class AdnuntiusSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'adnuntius_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'adnuntius.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('adnuntius.settings');

    $ad_units = $config->get('ad_units');

    $form['ad_units_label'] = [
      '#type' => 'label',
      '#title' => $this->t('Configure Ad Units'),
      '#suffix' => $this->t('Configure the available Ad Units on your site. Delete an Ad unit by removing the values. Make sure that you will loose the ads when removing an Ad Unit or changing the auId.'),
    ];
    $form['ad_units'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Ad Unit Label'),
        $this->t('auId'),
        $this->t('Width'),
        $this->t('Height'),
        $this->t('Weight'),
      ],
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'table-sort-weight',
        ],
      ],
      '#suffix' => $this->t('<dl><dt>Ad Unit Label:</dt><dd>The human readable Ad Unit label. It will be for selecting the Unit adding ads to your site.</dd><dt>auId:</dt><dd>The Adnuntius Ad unit Id.</dd><dt>Width:</dt><dd>The default width of the ad unit.</dd><dt>Height:</dt><dd>The default height of the ad unit.</dd></em>'),
    ];

    if (!empty($ad_units)) {
      foreach ($ad_units as $ad_unit) {
        $form['ad_units'][$ad_unit['auid']] = $this->buildRow($ad_unit);
      }
    }

    // Add an empty new row at the end of the table.
    $form['ad_units']['new'] = $this->buildRow();

    return parent::buildForm($form, $form_state);
  }

  /**
   * Build an ad unit configuration row.
   *
   * @param array|null $ad_unit
   *   The ad unit values.
   *
   * @return array
   *   A render array.
   */
  protected function buildRow(array $ad_unit = []) {
    $build = [];

    // Merge ad unit values with default ones.
    $ad_unit = $ad_unit + [
      'label' => '',
      'auid' => '',
      'width' => '',
      'height' => '',
      'weight' => 50,
    ];

    // TableDrag: Mark the table row as draggable.
    $build['#attributes']['class'][] = 'draggable';
    // TableDrag: Sort the table row according to its existing/configured
    // weight.
    $build['#weight'] = $ad_unit['weight'];

    $build['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Human readable label for the ad unit'),
      '#title_display' => 'invisible',
      '#default_value' => $ad_unit['label'],
      '#required' => FALSE,
    ];
    $build['auid'] = [
      '#type' => 'textfield',
      '#title' => $this->t('auId for @label', ['@label' => $ad_unit['label']]),
      '#title_display' => 'invisible',
      '#default_value' => $ad_unit['auid'],
      '#required' => FALSE,
    ];
    $build['width'] = [
      '#type' => 'number',
      '#title' => $this->t('Width for @label', ['@label' => $ad_unit['label']]),
      '#title_display' => 'invisible',
      '#default_value' => $ad_unit['width'],
      '#required' => FALSE,
    ];
    $build['height'] = [
      '#type' => 'number',
      '#title' => $this->t('Height for @label', ['@label' => $ad_unit['label']]),
      '#title_display' => 'invisible',
      '#default_value' => $ad_unit['height'],
      '#required' => FALSE,
    ];
    // TableDrag: Weight column element.
    $build['weight'] = [
      '#type' => 'weight',
      '#title' => $this->t('Weight for @label', ['@label' => $ad_unit['label']]),
      '#title_display' => 'invisible',
      '#default_value' => $ad_unit['weight'],
      // Classify the weight element for #tabledrag.
      '#attributes' => ['class' => ['table-sort-weight']],
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('adnuntius.settings');

    $ad_units = $form_state->getValue('ad_units');

    // Remove empty ad units, e.g. the new row item.
    $ad_units = array_filter($ad_units, function ($unit) {
      return !empty($unit['auid']);
    });

    // Use ad_unit as the key for a non-empty new entry.
    if (!empty($ad_units['new']['auid'])) {
      $ad_units[$ad_units['new']['auid']] = $ad_units['new'];
      unset($ad_units['new']);
    }

    $config->set('ad_units', $ad_units);
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
