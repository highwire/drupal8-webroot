<?php

namespace Drupal\adnuntius\Plugin\Block;

use Drupal\adnuntius\AdnuntiusManagerInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Creates a Adnuntius Block.
 *
 * @Block(
 *  id = "adnuntius_block",
 *  admin_label = @Translation("Adnuntius Block"),
 * )
 */
class AdnuntiusBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The adnuntius manager.
   *
   * @var \Drupal\adnuntius\AdnuntiusManagerInterface
   */
  protected $adnuntiusManager;

  /**
   * Constructs a new AdnuntiusBlock.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\adnuntius\AdnuntiusManagerInterface $adnuntius_manager
   *   The adnuntius manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AdnuntiusManagerInterface $adnuntius_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->adnuntiusManager = $adnuntius_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('adnuntius.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $config = $this->getConfiguration();

    $auId = $config['auid'];
    if ($adUnit = $this->adnuntiusManager->getAdUnit($auId)) {
      $build = $this->adnuntiusManager->render($auId, $config['invocation_method']);
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();
    $adUnits = $this->adnuntiusManager->getAdUnitsOptionList();
    $invocationMethods = $this->adnuntiusManager->getInvocationMethodOptionList();

    $form['auid'] = [
      '#type' => 'select',
      '#title' => $this->t('Ad Unit Id'),
      '#description' => $this->t('Select the Ad Unit to be displayed..'),
      '#default_value' => isset($config['auid']) ? $config['auid'] : '',
      '#options' => $adUnits,
      '#required' => TRUE,
    ];
    $form['invocation_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Invocation method'),
      '#description' => $this->t('Banner invocation method. How will the ads be displayed.'),
      '#default_value' => isset($config['invocation_method']) ? $config['invocation_method'] : 'div',
      '#options' => $invocationMethods,
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    if (!$form_state->getErrors()) {
      $this->setConfigurationValue('auid', $form_state->getValue('auid'));
      $this->setConfigurationValue('invocation_method', $form_state->getValue('invocation_method'));
    }
  }

}
