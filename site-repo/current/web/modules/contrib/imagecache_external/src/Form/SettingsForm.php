<?php

namespace Drupal\imagecache_external\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Locale\CountryManagerInterface;
use Drupal\Core\State\StateInterface;
use Drupal\user\UserStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a form that configures uploadcare settings.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new SettingsForm.
   *
   * @param \Drupal\Core\File\FileSystemInterface $fileSystem
   *   The file system service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(FileSystemInterface $fileSystem, ConfigFactoryInterface $config_factory) {
    $this->fileSystem = $fileSystem;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('file_system'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'imagecache_external_admin_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'imagecache_external.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('imagecache_external.settings');

    $form['imagecache_directory'] = [
      '#type' => 'textfield',
      '#title' => t('Imagecache Directory'),
      '#required' => TRUE,
      '#description' => t('Where, within the files directory, should the downloaded images be stored?'),
      '#default_value' => $config->get('imagecache_directory'),
      '#validate' => '::validateForm',
    ];

    $form['imagecache_default_extension'] = [
      '#type' => 'select',
      '#options' => [
        '' => 'none',
        '.jpg' => 'jpg',
        '.png' => 'png',
        '.gif' => 'gif',
        '.jpeg' => 'jpeg',
      ],
      '#title' => t('Imagecache default extension'),
      '#required' => FALSE,
      '#description' => t('If no extension is provided by the external host, specify a default extension'),
      '#default_value' => $config->get('imagecache_default_extension'),
    ];

    $form['imagecache_external_management'] = [
      '#type' => 'radios',
      '#title' => t('How should Drupal handle the files?'),
      '#description' => t('Managed files can be re-used elsewhere on the site, for instance in the Media Library if you use the Media module. Unmanaged files are not saved to the database, but can be cached using Image Styles.'),
      '#options' => [
        'unmanaged' => t('Unmanaged: Only save the images to the files folder to be able to cache them. This is  default.'),
        'managed' => t('Managed: Download the images and save its metadata to the database.'),
      ],
      '#default_value' => $config->get('imagecache_external_management'),
    ];

    $form['imagecache_external_use_whitelist'] = [
      '#type' => 'checkbox',
      '#title' => t('Use whitelist'),
      '#description' => t('By default, all images are blocked except for images served from white-listed hosts. You can define hosts below.'),
      '#default_value' => $config->get('imagecache_external_use_whitelist'),
    ];

    $form['imagecache_external_hosts'] = [
      '#type' => 'textarea',
      '#title' => t('Imagecache External hosts'),
      '#description' => t('Add one host per line. You can use top-level domains to whitelist subdomains. Ex: staticflickr.com to whitelist farm1.staticflickr.com and farm2.staticflickr.com'),
      '#default_value' => $config->get('imagecache_external_hosts'),
      '#states' => [
        'visible' => [
          ':input[name="imagecache_external_use_whitelist"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['imagecache_fallback_image'] = [
      '#type' => 'managed_file',
      '#name' => 'imagecache_fallback_image',
      '#title' => t('Fallback image'),
      '#description' => t("When an external image couldn't be found, use this image as a fallback."),
      '#default_value' => $config->get('imagecache_fallback_image'),
      '#upload_location' => 'public://',
    ];

    $form['imagecache_external_cron_flush_frequency'] = [
      '#type' => 'number',
      '#title' => $this->t('Cron cache flush frequency'),
      '#description' => $this->t('The flush frequency, represented as the number of days, for flushing cached images during cron. Enter 0 to disable cron flushing.'),
      '#field_suffix' => $this->t('number of days'),
      '#default_value' => $config->get('imagecache_external_cron_flush_frequency', 0),
      '#min' => 0,
      '#required' => TRUE,
    ];

    $form['#validate'][] = '::validateForm';

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $scheme = $this->configFactory->get('system.file')->get('default_scheme');
    $directory = $scheme . '://' . $form_state->getValue('imagecache_directory');
    if (!$this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY)) {
      $error = $this->t('The directory %directory does not exist or is not writable.', ['%directory' => $directory]);
      $form_state->setErrorByName('imagecache_directory', $error);
      $this->logger('imagecache_external')->error($error);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('imagecache_external.settings')
      ->set('imagecache_directory', $values['imagecache_directory'])
      ->set('imagecache_default_extension', $values['imagecache_default_extension'])
      ->set('imagecache_external_management', $values['imagecache_external_management'])
      ->set('imagecache_external_use_whitelist', $values['imagecache_external_use_whitelist'])
      ->set('imagecache_external_hosts', $values['imagecache_external_hosts'])
      ->set('imagecache_fallback_image', $values['imagecache_fallback_image'])
      ->set('imagecache_external_cron_flush_frequency', $values['imagecache_external_cron_flush_frequency'])
      ->save();
    parent::submitForm($form, $form_state);
  }

}
