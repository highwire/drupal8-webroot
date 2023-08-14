<?php

/**
 * @file
 * Contains Drupal\crazyegg\Form\CrazyeggSettingsForm.
 */

namespace Drupal\crazyegg\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\Element;

/**
 * Returns responses for Crazyegg module routes.
 */
class CrazyeggSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'crazyegg_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('crazyegg.settings')
        ->set('crazyegg_enabled', $form_state->getValue('crazyegg_enabled'))
        ->set('crazyegg_account_id', $form_state->getValue('crazyegg_account_id'))
        ->set('crazyegg_js_scope', $form_state->getValue('crazyegg_js_scope'))
        ->set('crazyegg_roles_excluded', $form_state->getValue('crazyegg_roles_excluded'))
        ->set('crazyegg_paths', $form_state->getValue('crazyegg_paths'))
        ->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['crazyegg.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $form = [];

    $form['crazyegg_heading'] = array(
      '#type' => 'item',
      '#markup' => $this->t('<img src="@logo" style="float: right;" alt="Crazy Egg">'
        . '<a href="@url">Crazy Egg</a> is an analytics tool that provides website heatmaps and eye tracking.<br/><br/>',
        array(
          '@url' => 'https://www.crazyegg.com',
          '@logo' => 'https://ceblog.s3.amazonaws.com/wp-content/uploads/2015/06/Crazy-Egg-logo-small.png'
        )
      ),
    );

    $form['crazyegg_enabled'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Crazy Egg Enabled?'),
      '#default_value' => \Drupal::config('crazyegg.settings')->get('crazyegg_enabled'),
      '#options' => array(
        1 => $this->t('Yes'),
        -1 => $this->t('No'),
      ),
    );

    $form['crazyegg_account_id'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Crazy Egg Account ID'),
      '#attributes' => array(
        'placeholder' => $this->t('e.g. 00111111'),
      ),
      '#default_value' => \Drupal::config('crazyegg.settings')->get('crazyegg_account_id'),
      '#description' => $this->t('This is your numerical CrazyEgg account ID, it is 8 digits long.<br/>'
        . 'The easiest way to find it is by logging in to your CrazyEgg account.<br/>'
        . 'Click on Account. Under your profile and email address, you’ll see your account number.'),
    );

    $form['advanced_settings'] = array(
      '#type' => 'details',
      '#title' => $this->t('More settings'),
      '#open' => FALSE,
    );

    $form['advanced_settings']['crazyegg_js_scope'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Location to add the tracking script'),
      '#description' => $this->t('Controls where on the page the tracking script is added'),
      '#default_value' => \Drupal::config('crazyegg.settings')->get('crazyegg_js_scope'),
      '#options' => array(
        'header' => $this->t('Header <em>(recommended)</em>'),
        'footer' => $this->t('Footer'),
      ),
    );

    $form['advanced_settings']['crazyegg_roles_excluded'] = array(
      '#type' => 'checkboxes',
      '#options' => user_role_names(),
      '#title' => $this->t('Excluded roles (optional)'),
      '#default_value' => \Drupal::config('crazyegg.settings')->get('crazyegg_roles_excluded'),
      '#description' => $this->t('You can control which visits and clicks are tracked in Crazy Egg by excluding roles.<br/>'
        . 'For example, if you have traffic generated by employees, it’s difficult to distinguish visits '
        . 'from your visitors versus those visits from your own employees.<br/>'
        . 'To prevent internal traffic (i.e. administrators) from diluting your data, select "Administrator."'),
    );

    $form['advanced_settings']['crazyegg_paths'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Pages to track (optional)'),
      '#default_value' => \Drupal::config('crazyegg.settings')->get('crazyegg_paths'),
      '#description' => $this->t('By default, Crazy Egg will track all pages within your domain.<br/>'
        . 'Need to track specific pages instead of all pages? You can specify which pages you\'d like to track '
        . 'by providing the path (everything after .com). Include one path per line. For example,'
        . '<pre>  /home/about<br/>  /posts<br/>  /posts/*<br/>  /users/*/details</pre>'),
      '#cols' => 100,
      '#rows' => 5,
      '#resizable' => FALSE,
      '#required' => FALSE,
      '#weight' => 40,
    );

    $form['crazyegg_help'] = array(
      '#type' => 'item',
      '#markup' => $this->t(
          '<em>Note: if you don\'t get the desired effect after changing some settings, try clearing Drupal cache.</em><br/><br/>'
        . '<strong>Support:</strong> <a href="mailto:support@crazyegg.com">support@crazyegg.com</a><br />'
        . '<strong>Website: </strong><a href="https://www.crazyegg.com" target="_blank">https://www.crazyegg.com</a>'),
    );

    return parent::buildForm($form, $form_state);
  }

}