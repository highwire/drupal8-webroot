<?php

namespace HighWire\Utility;

class Date {

  const DATE_TYPE_XML = 'xml';
  const DATE_TYPE_ISO8601 = 'iso8601';
  const DATE_TYPE_TIMESTAMP = 'timestamp';
  const DATE_TYPE_UNKOWN = 'unknown';

  /**
   * Given a date, try to determine it's format
   *
   * @param string $date
   *   A string date.
   *
   * @return string
   *   The date type.
   */
  public static function determineFormat($date) {
    if (Str::startsWith('<', $date)) {
      return Date::DATE_TYPE_XML;
    }

    try {
      $date_time = new \DateTime($date);
      if ($date_time) {
        return Date::DATE_TYPE_ISO8601;
      }
    }
    catch (\Exception $e) {
      // Using the exception to detect ISO date, nothing to do here.
    }

    if (preg_match("/^[0-9]+(\.[0-9]+)?$/", $date)) {
      return Date::DATE_TYPE_TIMESTAMP;
    }

    return Date::DATE_TYPE_UNKOWN;
  }

  /**
   * Given a date, parse it and return it as a DateTime object
   *
   * @param mixed $date
   *   The date to parse.
   * @param string|null $format
   *   The format, if known.
   *
   * @return \DateTime|null
   *   A DateTime object.
   *
   * @throws \Exception
   */
  public static function parseDate($date, $format = NULL) {

    if (empty($format)) {
      $format = Date::determineFormat($date);
    }
    if ($format == Date::DATE_TYPE_ISO8601) {
      return Date::parseISO8601($date);
    }
    if ($format == Date::DATE_TYPE_TIMESTAMP) {
      return Date::parseUnixTimestamp($date);
    }
    if ($format == Date::DATE_TYPE_XML) {
      //TODO - write XML parser for date and times;
    }
    if ($format == Date::DATE_TYPE_UNKOWN) {
      return new \DateTime($date);
    }

    return NULL;
  }

  /**
   * Parse a unix timestmap into a DateTime object
   */
  public static function parseUnixTimestamp($date) {
    $date = new \DateTime('@' . intval($date));
    if ($date) {
      return $date;
    }

    return NULL;
  }

  /**
   * Parse ISO-8601 (and pseudo iso-8601) dates into a DateTime object
   */
  public static function parseISO8601($date) {
    // Break a date down by parts
    // Longest potential format: 2017-02-06T14:25:31.395-08:00

    $date_time = explode('T', $date);
    $full_date = $date_time[0];
    $date_parts = explode('-', $full_date);
    $year = $date_parts[0];
    $month = isset($date_parts[1]) ? $date_parts[1] : '01';
    $day = isset($date_parts[2]) ? $date_parts[2] : '01';

    if ($full_time = isset($date_time[1]) ? $date_time[1] : NULL) {
      $time_zone_parts = explode('-', $full_time);
      $time_zone = isset($time_zone_parts[1]) ? $time_zone_parts[1] : '-00:00';
      $time_parts_milli = explode('.', $time_zone_parts[0]);
      $time_parts = explode(':', $time_parts_milli[0]);
      $hour = $time_parts[0];
      $minutes = isset($time_parts[1]) ? $time_parts[1] : '00';
      $seconds = isset($time_parts[2]) ? $time_parts[2] : '00';
    }

    $assembled = $year;
    $format = 'Y';
    if (!empty($month)) {
      $assembled .= '-' . $month;
      $format .= '-m';
    }
    if (!empty($day)) {
      $assembled .= '-' . $day;
      $format .= '-d';
    }
    if (isset($full_time)) {
      $assembled .= 'T' . $hour . ':' . $minutes . ':' . $seconds;
      $format .= '\TH:i:s';
    }
    if (isset($time_zone)) {
      $assembled .= '-' . $time_zone;
      $format .= 'P';
    }

    $date = \DateTime::createFromFormat($format, $assembled);
    if ($date) {
      return $date;
    }

    return NULL;
  }

}
