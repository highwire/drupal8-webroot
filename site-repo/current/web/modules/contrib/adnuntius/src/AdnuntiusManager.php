<?php

namespace Drupal\adnuntius;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Manages the Adnuntius ads.
 */
class AdnuntiusManager implements AdnuntiusManagerInterface {

  use StringTranslationTrait;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a new AdnuntiusManager object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, RendererInterface $renderer) {
    $this->configFactory = $config_factory;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public function getAdUnits() {
    $config = $this->configFactory->get('adnuntius.settings');
    $ad_units = $config->get('ad_units');

    return $ad_units;
  }

  /**
   * {@inheritdoc}
   */
  public function getAdUnit($auId) {
    $ad_unit = NULL;
    $ad_units = $this->getAdUnits();

    // Check if the ad unit is available.
    if (isset($ad_units[$auId])) {
      $ad_unit = $ad_units[$auId];
    }

    return $ad_unit;
  }

  /**
   * {@inheritdoc}
   */
  public function getAdUnitsOptionList() {
    $options = [];
    $ad_units = $this->getAdUnits();

    foreach ($ad_units as $ad_unit) {
      $options[$ad_unit['auid']] = sprintf('%s (%dx%d)', $ad_unit['label'], $ad_unit['width'], $ad_unit['height']);
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function getInvocationMethodOptionList() {
    return [
      'iframe' => $this->t('Iframe'),
      'div' => $this->t('Div'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function render($auId, $invocation_method) {
    $build = [];
    $config = $this->configFactory->get('adnuntius.settings');

    if ($adUnit = $this->getAdUnit($auId)) {
      $build = [
        '#theme' => 'adnuntius',
        '#label' => $adUnit['label'],
        '#auid' => $adUnit['auid'],
        '#width' => $adUnit['width'],
        '#height' => $adUnit['height'],
        '#invocation_method' => $invocation_method,
      ];
    }

    $this->renderer->addCacheableDependency($build, $config);

    return $build;
  }

}
