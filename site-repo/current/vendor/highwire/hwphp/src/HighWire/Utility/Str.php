<?php

namespace HighWire\Utility;

/**
 * Str Utility Class.
 */
class Str {

  /**
   * Check if needle is in haystack.
   *
   * @param string $needle
   *   String to search for.
   * @param string $haystack
   *   String match against.
   *
   * @return bool
   *   TRUE if string exists, otherwise FALSE.
   */
  public static function contains($needle, $haystack) {
    if (mb_strpos($haystack, $needle) !== FALSE) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check if haystack starts with needle.
   *
   * @param mixed $needle
   *   String to search for. Can be single string, or array of strings.
   * @param string $haystack
   *   String match against.
   *
   * @return bool
   *   TRUE if string starts with the needle, otherwise FALSE.
   */
  public static function startsWith($needle, $haystack) {
    if (is_array($needle)) {
      foreach ($needle as $subneedle) {
        if (self::startsWith($subneedle, $haystack)) {
          return TRUE;
        }
      }
      return FALSE;
    }
    return !strncmp($haystack, $needle, strlen($needle));
  }

  /**
   * Check if haystack ends with needle.
   *
   * @param mixed $needle
   *   String to search for. Can be single string or array of strings.
   * @param string $haystack
   *   String match against.
   *
   * @return bool
   *   TRUE if string ends with the needle, otherwise FALSE.
   */
  public static function endsWith($needle, $haystack) {
    if (is_array($needle)) {
      foreach ($needle as $subneedle) {
        if (self::endsWith($subneedle, $haystack)) {
          return TRUE;
        }
      }
      return FALSE;
    }
    else {
      $length = strlen($needle);
      if ($length == 0) {
        return TRUE;
      }
      return (substr($haystack, -$length) === $needle);
    }
  }

  /**
   * Trim to the nearest word.
   *
   * @param string $string
   *   The string to trim.
   * @param int $len
   *   The max length of the string.
   * @param string $ellipsis
   *   The elilipsis to add to the end of the string.
   *
   * @return string
   *   The truncated string.
   */
  public static function wordTrim($string, $len, $ellipsis = '') {
    $elen = strlen($ellipsis);
    return (strlen($string) > $len) ? substr($string, 0, strpos(wordwrap($string, ($len - $elen)), "\n")) . $ellipsis : $string;
  }

  /**
   * Sanitize the string.
   *
   * Applications like drupal don't like - in the machine names.
   * To make life easier enforeced this at the hwphp level.
   *
   * @param string $string
   *   A string to sanitize.
   *
   * @return string
   *   A sanitized string
   */
  public static function sanitizeMachineName($string) {
    return str_replace('-', '_', $string);
  }

  /**
   * Do the reverse of sanitizeMachineName
   *
   * @param string $string
   *   The sanitized string.
   *
   * @return string
   *   The unsanitized string.
   */
  public static function unsanitizeMachineName($string) {
    return str_replace('_', '-', $string);
  }

}
