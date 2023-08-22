<?php

namespace HighWire\Parser\ExtractPolicy;

use HighWire\Parser\DOMElementBase;

/**
 * Field holds a single "<variable>" element.
 *
 * For example:
 *  @code
 *  @endcode
 */
class Variable extends DOMElementBase {

  /**
   * Get the field name.
   *
   * @return string
   *   The name as defined in the 'name' attribute.
   */
  public function name(): string {
    return $this->getAttribute('name');
  }

  /**
   * Get the xpath that needs to be true for this variable to be included.
   *
   * @return string
   *   The xpath as defined in the 'include-if' attribute.
   */
  public function includeIf(): string {
    return $this->getAttribute('include-if');
  }

  /**
   * Get the xpath that this variable uses to load value.
   *
   * @return string
   *   The xpath for the variable.
   */
  public function xpathValue(): string {
    return $this->elem->textContent;
  }

}
