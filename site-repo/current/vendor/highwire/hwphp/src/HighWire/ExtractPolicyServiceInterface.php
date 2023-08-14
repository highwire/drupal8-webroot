<?php

namespace HighWire;

/**
 * The extract policy service interface should be implement if the class
 * has the ability to return extract policies from a source.
 */
interface ExtractPolicyServiceInterface {

  /**
   * Get an extract policy from atomlite.
   *
   * @param string $policy_name
   *   The name of the policy you're interested in.
   *
   * @throws HighWire\Exception\PolicyNotFoundException
   */
  public function getRawExtractPolicy(string $policy_name);

}
