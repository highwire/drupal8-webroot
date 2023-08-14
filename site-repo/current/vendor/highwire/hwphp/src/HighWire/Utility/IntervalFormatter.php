<?php

namespace HighWire\Utility;

/**
 * Provides Date Interval formatters.
 */
class IntervalFormatter {

  /**
   * Returns date interval hours.
   *
   * @return \Closure
   *   Returns date interval formatter closure.
   */
  public static function hours() {
    return function(\DateInterval $interval) {
      $hours = abs((new \DateTime)->add($interval)->getTimestamp() - (new \DateTime)->getTimestamp()) / 60 / 60;
      return $hours;
    };
  }

  /**
   * Returns date interval as readable year(s), month(s), day(s) and hour(s).
   *
   * @param string $separator
   *   String to separate date parts.
   *
   * @return \Closure
   *   Returns date interval formatter closure.
   */
  public static function readable($separator = ', ') {
    return function(\DateInterval $interval) use ($separator) {
      $diff = (new \DateTime)->diff((new \DateTime)->add($interval));
      $output = [];
      $pluralize = function ($val, $text) { return ($val > 1) ? $text . 's' : $text; };

      if ($diff->y > 0) {
        $output[] = $diff->format('%y ' . $pluralize($diff->y, 'year'));
      }

      if ($diff->m > 0) {
        $output[] = $diff->format('%m ' . $pluralize($diff->m, 'month'));
      }

      if ($diff->d > 0) {
        $output[] = $diff->format('%d ' . $pluralize($diff->d, 'day'));
      }

      if ($diff->h > 0) {
        $output[] = $diff->format('%h ' . $pluralize($diff->h, 'hour'));
      }

      return rtrim(implode($separator, $output), $separator);
    };
  }

}
