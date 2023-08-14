<?php

namespace HighWire\Utility;

use Symfony\Component\Yaml\Yaml;

/**
 * Utility Class for checking if IP is trusted IP Address.
 */
class TrustedIP {

  /**
   * Checks to see if the given IP address is a HighWire IP.
   *
   * See trusted_ip.yml for list of trusted IP ranges.
   *
   * @param string $ip
   *   IP Address or CIDR range.
   * @param array $additional_ranges
   *   Any additional ranges that should be trusted (in CIDR format).
   *
   * @return bool
   *   TRUE if the IP is trusted, FALSE if the IP is not trusted.
   */
  public static function isTrusted($ip, array $additional_ranges = []) {

    static $trusted_ranges;

    // If it's not a valid IP Address, it's not trusted.
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
      return FALSE;
    }
    // If it's a local address or reserved address, it's trusted.
    if (self::isPrivateIP($ip)) {
      return TRUE;
    }

    // Build list of always-trusted ranges.
    if (empty($trusted_ranges)) {
      $yaml = Yaml::parse(file_get_contents(__DIR__ . '/trusted_ip.yml'));
      foreach ($yaml['trusted_ip'] as $range) {
        $trusted_ranges[] = \IPBlock::create($range);
      }
    }

    // Build list of ranges to check (trusted-ranges ∪ additional-ranges)
    $ranges = $trusted_ranges;
    foreach ($additional_ranges as $range) {
      $ranges[] = \IPBlock::create($range);
    }

    // Check to see if the IP is in one of the ranges.
    foreach ($ranges as $block) {
      if ($block->contains($ip)) {
        return TRUE;
      }
    }

    // Not contained in any range.
    return FALSE;
  }

  /**
   * Checks to see if the given IP is private.
   *
   * An IP address is considered private if it falls in one of these ranges:
   *  - 10.0.0.0/8
   *  - 172.16.0.0/12
   *  - 192.168.0.0/16
   *  - 0.0.0.0/8
   *  - 169.254.0.0/16
   *  - 127.0.0.0/8
   *  - 240.0.0.0/4
   *  - ::1/128
   *  - ::/128
   *  - ::ffff:0:0/96
   *  - fe80::/10
   *
   * See https://en.wikipedia.org/wiki/Reserved_IP_addresses for more info.
   *
   * @param string $ip
   *   IP Address.
   *
   * @return bool
   *   TRUE if the IP is private, FALSE otherwise.
   */
  public static function isPrivateIP($ip) {
    return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
  }

}
