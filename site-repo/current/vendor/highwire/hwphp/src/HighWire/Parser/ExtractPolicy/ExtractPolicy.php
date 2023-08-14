<?php

namespace HighWire\Parser\ExtractPolicy;

use BetterDOMDocument\DOMDoc;
use HighWire\Exception\HighWireExtractPolicyInvalid;

/**
 * Extract Service Class.
 *
 * Helper class for parsing the extract policy.
 */
class ExtractPolicy extends DOMDoc {

  /**
   * Get a field.
   *
   * @param string $field_name
   *   The field name.
   *
   * @return \HighWire\Parser\ExtractPolicy\Field|null
   *   A field object, or NULL if none exists.
   */
  public function getField($field_name) {
    $element = $this->xpathSingle("./policy:field[@name='$field_name']");
    if ($element) {
      return new Field($element, $this);
    }
  }

  /**
   * Check if a field exists.
   *
   * @param string $field_name
   *   The field name.
   *
   * @return bool
   *   True or False depending on if the field exists.
   */
  public function fieldExists($field_name): bool {
    $element = $this->xpathSingle("./policy:field[@name='$field_name']");
    if ($element) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Get all fields
   * 
   * @param string|object|null $context
   *   XML context. Can be xpath string or DOMElement.
   * 
   * @return \HighWire\Parser\ExtractPolicy\Field[]
   *   Array of fields keyed by name.
   */
  public function fields($context = NULL): array {
    $elements = $this->xpath('./policy:field', $context);

    $fields = [];
    foreach ($elements as $element) {
      $field = new Field($element, $this);
      $fields[$field->name()] = $field;
    }

    return $fields;
  }

  /**
   * Get a list of all fields (even nested ones) as a flat array.
   * 
   * @return \HighWire\Parser\ExtractPolicy\Field[]
   *   Array of fields keyed by path.
   */
  public function flatFields(): array {
    $elements = $this->xpath('//policy:field');

    $fields = [];
    foreach ($elements as $element) {
      $field = new Field($element, $this);
      $fields[$field->path()] = $field;
    }

    return $fields;
  }

  /**
   * Get variables from the extract policy.
   *
   * @param string|object|null $context
   *   XML context. Can be xpath string or DOMElement.
   * 
   * @return \HighWire\Parser\ExtractPolicy\Variable[]
   *   Array of variables keyed by name.
   */
  public function variables($context = NULL): array {
    $elements = $this->xpath('policy:variable', $context);
    $variables = [];
    if ($elements) {
      foreach ($elements as $element) {
        $variable = new Variable($element, $this);
        $variables[$variable->name()] = $variable;
      }
    }
    return $variables;
  }

  /**
   * Get the name.
   * 
   * @deprecated Use getPolicyId().
   * 
   * @return string
   *   The policy-id
   * 
   * @see ExtractPolicy::getPolicyId();
   */
  public function getName(): string {
    return $this->getPolicyId();
  }

  /**
   * Get the policy-id.
   * 
   * @return string
   *   The policy-id as defined in the 'policy-id' attribute
   */
  public function getPolicyId(): string {
    $policy_id = $this->documentElement->getAttribute('policy-id');

    // Backwards compatibility
    if ($policy_id) {
      return $policy_id;
    }
    else {
      return $this->documentElement->getAttribute('name');
    }
  }

  /**
   * Get the primary id field.
   * 
   * @return string
   *   The primary ID field, as defined by the 'id-field' attribute.
   */
  public function getPrimaryIdField(): string {
    return $this->documentElement->getAttribute('id-field');
  }

  /**
   * Get the courpus field.
   * 
   * @return string
   *   The field that contains the corpus code, as defined by the 'corpus-field' attribute.
   */
  public function getCorpusField(): string {
    return $this->documentElement->getAttribute('corpus-field');
  }

  /**
   * Get type field.
   * 
   * @return string
   *   The field that contains the type, as defined by the 'type-field' attribute.
   */
  public function getTypeField(): string {
    return $this->documentElement->getAttribute('type-field');
  }

  /**
   * Return a list of arrays that 
   * 
   * @return string[]
   *   List of applications that this policy has been marked for use by.
   */
  public function applications(): array {
    $applications = explode(' ', $this->documentElement->getAttribute('applications'));
    $applications = array_filter($applications);
    return $applications;
  }

  /**
   * Check if the policy has been marked for use by a given application.
   * 
   * @param string $application
   *   The application to check.
   * 
   * @return bool
   *   Returns TRUE if the application is the in "applications" attribute.
   */
  public function hasApplication(string $application): bool {
    return in_array($application, $this->applications(), TRUE);
  }

  /**
   * Verify that the Extract Policy is correct and error free.
   *
   * This function will throw an exception of the type HighWireExtractPolicyInvalid if the policy contains errors.
   * 
   * @throws \HighWire\Exception\HighWireExtractPolicyInvalid
   */
  public function verify() {

    // Check basic attributes.
    if (empty($this->documentElement->getAttribute('policy-id'))) {
      throw new HighWireExtractPolicyInvalid("Missing 'policy-id' attribute");
    }
    if (empty($this->getCorpusField())) {
      throw new HighWireExtractPolicyInvalid("Missing 'corpus-field' attribute");
    }
    if (empty($this->getPrimaryIdField())) {
      throw new HighWireExtractPolicyInvalid("Missing 'id-field' attribute");
    }
    if (empty($this->getTypeField())) {
      throw new HighWireExtractPolicyInvalid("Missing 'type-field' attribute");
    }
    if ($this->hasApplication('drupal')) {

      if (!$this->hasApplication('atomx')) {
        throw new HighWireExtractPolicyInvalid("Policies marked for use by drupal also must be marked for atomx.");
      }

      $reserved_fields = ['nid', 'vid', 'type', 'uuid', 'langcode'];
      $fields = array_keys($this->fields());
      foreach ($reserved_fields as $field) {
        if (in_array($field, $fields)) {
          throw new HighWireExtractPolicyInvalid("Field `$field` is a drupal reserved field and may not be present in an extract policy.");
        }
      }
    }

    // Check each field
    $fields = $this->flatFields();
    foreach ($fields as $field) {
      $field->verify();
    }

    // Check for duplicate field paths
    $fieldpaths = [];
    $elements = $this->xpath('//policy:field');
    foreach ($elements as $element) {
      $field = new Field($element, $this);
      $path = strtolower($field->path());
      if (in_array($path, $fieldpaths)) {
        throw new HighWireExtractPolicyInvalid("Duplicate fields found for $path");
      }
      $fieldpaths[] = $path;
    }

  }

}
