<?php

namespace HighWire\Parser\Markup;

/**
 * MarkupProcessor.
 */
interface MarkupProcessor {

  /**
   * Get an ID for the processor.
   */
  public function id();

  /**
   * Given markup, modify it according to the passed context.
   */
  public function process(Markup $markup, $context = NULL);

}
