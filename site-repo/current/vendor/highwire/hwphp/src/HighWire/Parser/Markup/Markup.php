<?php

namespace HighWire\Parser\Markup;

use BetterDOMDocument\DOMDoc;

/**
 * Extract Service Class.
 *
 * Helper class for parsing the extract policy.
 */
class Markup extends DOMDoc {
  protected $processors = [];
  protected $context;

  /**
   * Construct a markup document.
   *
   * @param string|\DOMDocument $xml
   *   The xml string to contruct the markup element from.
   * @param bool $auto_register_namespaces
   *   Namespaces to register.
   * @param bool|string $error_checking
   *   Domdoc error level.
   */
  public function __construct($xml = '', $auto_register_namespaces = TRUE, $error_checking = 'strict') {
    parent::__construct($xml, $auto_register_namespaces, $error_checking);

    // Always register the xhtml namespace.
    $this->registerNamespace('xhtml', 'http://www.w3.org/1999/xhtml');
  }

  /**
   * Add a processor to the markup.
   *
   * The processor will only be triggered when the markup is outputted.
   *
   * @param \HighWire\Parser\Markup\MarkupProcessor $processor
   *   The processor to add.
   */
  public function AddProcessor(MarkupProcessor $processor) {
    $this->processors[] = $processor;
  }

  /**
   * Set the context on the markup. eg, a node from Drupal.
   *
   * @param mixed $context
   *   Can be anything to provide additional context for the processor.
   */
  public function setContext($context) {
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function out($domcontext = NULL) {
    foreach ($this->processors as $processor) {
      $processor->process($this, $this->context);
    }
    return parent::out($domcontext);
  }

  /**
   * Load markup from a URL.
   *
   * It will parse the markup as it is being downloaded from the URL in a streaming fashion.
   * This is the preferred method of loading markup from a remote service.
   */
  public static function loadMarkup($url) {
    $dom = new \DOMDocument();
    $success = $dom->load($url, LIBXML_COMPACT);
    if (empty($success)) {
      return FALSE;
    }

    return new Markup($dom);
  }

}
