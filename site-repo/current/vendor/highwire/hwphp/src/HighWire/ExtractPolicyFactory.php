<?php

namespace HighWire;

use HighWire\Parser\ExtractPolicy\ExtractPolicy;

/**
 * Extract policy factory is a wrapping class for getting the extract policy from different sources.
 */
class ExtractPolicyFactory {

  /**
   * Policy service.
   *
   * @var \HighWire\ExtractPolicyServiceInterface
   */
  protected $policyService;

  /**
   * Construct a new extract policy factory.
   *
   * @param \HighWire\ExtractPolicyServiceInterface $extract_policy_service
   *   An class that implments the extract policy service interface.
   */
  public function __construct(ExtractPolicyServiceInterface $extract_policy_service) {
    $this->policyService = $extract_policy_service;
  }

  /**
   * Get an extract policy.
   */
  public function get(string $policy_name) {
    return new ExtractPolicy($this->policyService->getRawExtractPolicy($policy_name));
  }

}
