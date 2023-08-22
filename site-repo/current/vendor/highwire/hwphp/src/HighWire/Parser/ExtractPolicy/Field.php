<?php

namespace HighWire\Parser\ExtractPolicy;

use HighWire\Parser\DOMElementBase;
use HighWire\Exception\HighWireExtractPolicyInvalid;

/**
 * Field holds a single "<field>" element, which may have more <field> elements nested as children in a <structure>.
 *
 * For example:
 *  @code
 *  @endcode
 */
class Field extends DOMElementBase {

  const NAME_PATTERN = "/^[0-9,a-z,A-Z,-]+$/";

  const BOOL_VALS = ['true', 'false'];

  const TYPE_VALS = ['boolean', 'float', 'integer', 'text', 'structure'];

  const ELASTIC_VALS = ['string', 'text', 'keyword', 'long', 'integer', 'short', 'byte',
                        'double', 'float', 'half_float', 'scaled_float', 'date', 'boolean',
                        'binary', 'integer_range', 'float_range', 'long_range', 'double_range',
                        'date_range', 'object', 'nested', 'geo_point', 'geo_shape', 'ip',
                        'completion', 'token_count', 'murmur3', 'attachment', 'join'];

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
   * Get the field label.
   *
   * @return string
   *   The label as defined in the 'label' attribute.
   */
  public function label(): string {
    return $this->getAttribute('label');
  }

  /**
   * Get the field description.
   *
   * @return string
   *   The description as defined in the 'description' attribute.
   */
  public function description(): string {
    return $this->getAttribute('description');
  }

  /**
   * Get the field drupal type.
   *
   * @return string
   *   The drupal-type as defined in the 'drupal-type' attribute.
   */
  public function drupalType(): string {
    return $this->getAttribute('drupal-type');
  }

  /**
   * Get the field elastic type.
   *
   * @return string
   *   The elastic-type as defined in the 'elastic-type' attribute.
   */
  public function elasticType(): string {
    return $this->getAttribute('elastic-type');
  }

  /**
   * Get the field type
   *
   * @return string
   *   The type as defined in the 'type' attribute.
   */
  public function type(): string {
    $type = $this->getAttribute('type');
    if (empty($type)) {
      $type = 'text';
    }
    return $type;
  }

  /**
   * Determine if the field is a multi-value list.
   *
   * @return bool
   *   The name as defined in the 'name' attribute.
   */
  public function list(): bool {
    return $this->getAttribute('list') == 'true';
  }

  /**
   * Get the xpath that needs to be true for this field to be included.
   *
   * @return string
   *   The xpath as defined in the 'include-if' attribute.
   */
  public function includeIf(): string {
    return $this->getAttribute('include-if');
  }

  /**
   * Get the xpath that this field uses to fetch a value.
   *
   * In the case of a structure field, it will be the context.
   *
   * @return string
   *   The xpath as defined in the value or
   */
  public function xpathValue(): string {
    if ($this->type() == 'structure') {
      $structure = $this->xpathSingle('policy:structure');
      return $structure->getAttribute('context');
    }
    else {
      return $this->elem->textContent;
    }
  }

  /**
   * Get the JSON path for the values that this field generates.
   *
   * @return string
   *   The JSON path for this field.
   */
  public function path(): string {
    $path = '';
    $ancestors = $this->xpath('./ancestor-or-self::policy:field');
    $i = 0;
    foreach ($ancestors as $ancestor) {
      if ($i != 0) {
        $path .= ".";
      }
      $path .= $ancestor->getAttribute('name');
      $i++;
    }

    return $path;
  }

  /**
   * For a structure field, get a list of all subfields.
   *
   * @return \HighWire\Parser\ExtractPolicy\Field[]
   *   List of fields, keyed by name
   */
  public function structure(): array {
    $structure = [];
    $children = $this->xpath('./policy:structure/policy:field');
    if (!empty($children)) {
      foreach ($children as $child) {
        $field = new Field($child, $this->dom);
        $structure[$field->name()] = $field;
      }
    }
    return $structure;
  }

  /**
   * Verify that a field is sementaically correct.
   *
   * Throws an error if invalid.
   *
   * @throws \HighWire\Exception\HighWireExtractPolicyInvalid
   */
  public function verify() {
    if (!preg_match(self::NAME_PATTERN, $this->name())) {
      throw new HighWireExtractPolicyInvalid("Invalid field name " . $this->name());
    }
    if ($this->getAttribute('list') != '') {
      if (!in_array($this->getAttribute('list'), self::BOOL_VALS, TRUE)) {
        throw new HighWireExtractPolicyInvalid("Invalid list property value for field " . $this->name());
      }
    }
    if ($this->type()) {
      if (!in_array($this->type(), self::TYPE_VALS, TRUE)) {
        throw new HighWireExtractPolicyInvalid("Invalid type property value for field " . $this->name());
      }
    }
    if ($this->elasticType()) {
      if (!in_array($this->elasticType(), self::ELASTIC_VALS, TRUE)) {
        throw new HighWireExtractPolicyInvalid("Invalid elastic-type property value for field " . $this->name());
      }
    }

  }

}
