<?php

namespace Drupal\pingdom_rum\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Admin settings form for Pingdom RUM module.
 */
class PingdomRumAdminSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pingdom_rum_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('pingdom_rum.settings');
    $config->set('project_id', $form_state->getValue('project_id'));
    $config->set('visibility_pages', $form_state->getValue('visibility_pages'));
    $config->set('pages', $form_state->getValue('pages'));
    $config->set('roles_type', $form_state->getValue('roles_type'));
    $config->set('roles', $form_state->getValue('roles'));
    $config->save();

    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['pingdom_rum.settings'];
  }

  /**
   * Override function to build the form elements we need.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('pingdom_rum.settings');
    $form['account'] = [
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#title' => $this->t('General account settings'),
    ];

    $form['account']['project_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('The project ID given to you by Pingdom for your site'),
      '#default_value' => $config->get('project_id'),
      '#description' => $this->t('The Project ID given you by Pingdom. This is usually 24 characters long, and will only comprise hexadecimal characters. See the README file for more information.'),
      '#required' => TRUE,
    ];

    $form['scope_title'] = [
      '#type' => 'item',
      '#title' => $this->t('Pingdom RUM monitoring scope'),
    ];

    $form['scope_group'] = [
      '#type' => 'vertical_tabs',
      '#attached' => [
        'library' => [
          'pingdom_rum/pingdom_rum.admin',
        ],
      ],
    ];

    $form['scope']['page_vis_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Pages'),
      '#group' => 'scope_group',
    ];

    $visibility = $config->get('visibility_pages');

    $description = $this->t("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are %blog for the blog page and %blog-wildcard for every personal blog. %front is the front page.", [
      '%blog' => '/blog',
      '%blog-wildcard' => '/blog/*',
      '%front' => '<front>',
    ]);

    $form['scope']['page_vis_settings']['visibility_pages'] = [
      '#type' => 'radios',
      '#title' => $this->t('Add Pingdom RUM monitoring code to specific pages'),
      '#options' => [
        $this->t('Every page except the listed pages'),
        $this->t('The listed pages only'),
      ],
      '#default_value' => $visibility,
    ];

    $form['scope']['page_vis_settings']['pages'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Pages'),
      '#title_display' => 'invisible',
      '#default_value' => $config->get('pages'),
      '#description' => $description,
      '#rows' => 10,
    ];

    $form['scope']['role_vis_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Roles'),
      '#group' => 'scope_group',
    ];

    $form['scope']['role_vis_settings']['roles_type'] = [
      '#type' => 'radios',
      '#title' => $this->t('Add Pingdom RUM monitoring code for specific roles'),
      '#options' => [
        $this->t('Add to the selected roles only'),
        $this->t('Add to every role except the selected ones'),
      ],
      '#default_value' => $config->get('roles_type'),
    ];

    $role_options = array_map('\Drupal\Component\Utility\Html::escape', user_role_names());

    $form['scope']['role_vis_settings']['roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Roles'),
      '#default_value' => $config->get('roles'),
      '#options' => $role_options,
      '#description' => $this->t('If none of the roles are selected, all users will be tracked. If a user has any of the roles checked, that user will be tracked (or excluded, depending on the setting above).'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Callback function to check the project ID is a hexadecimal string.
   *
   * This sanitises the input.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $new_value = $form_state->getValue(['project_id']);
    if (!ctype_xdigit($new_value)) {
      $form_state->setErrorByName('project_id', $this->t('You must enter a hexadecimal string'));
    }
  }

}
