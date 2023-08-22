<?php

use HighWire\Utility\IntervalFormatter;
use PHPUnit\Framework\TestCase;

class IntervalFormatterTest extends TestCase {

  public function testHours() {
    $formatter = IntervalFormatter::hours();
    $hours_12 = $formatter(new \DateInterval('P0Y0M00DT12H0M0S'));
    $hours_48 = $formatter(new \DateInterval('P0Y0M02DT0H0M0S'));
    $hours_168 = $formatter(new \DateInterval('P0Y0M07DT0H0M0S'));

    $this->assertEquals('12', $hours_12);
    $this->assertEquals('48', $hours_48);
    $this->assertEquals('168', $hours_168);
  }

  public function testReadableYears() {
    $formatter = IntervalFormatter::readable();
    $years_5 = $formatter(new \DateInterval('P5Y0M0DT0H0M0S'));
    $years_1 = $formatter(new \DateInterval('P1Y0M0DT0H0M0S'));

    $this->assertEquals('5 years', $years_5);
    $this->assertEquals('1 year', $years_1);
  }

  public function testReadableMonths() {
    $formatter = IntervalFormatter::readable();
    $months_6 = $formatter(new \DateInterval('P0Y6M0DT0H0M0S'));
    $months_1 = $formatter(new \DateInterval('P0Y1M0DT0H0M0S'));

    $this->assertEquals('6 months', $months_6);
    $this->assertEquals('1 month', $months_1);
  }

  public function testReadableDays() {
    $formatter = IntervalFormatter::readable();
    $days_14 = $formatter(new \DateInterval('P0Y0M14DT0H0M0S'));
    $days_1 = $formatter(new \DateInterval('P0Y0M1DT0H0M0S'));

    $this->assertEquals('14 days', $days_14);
    $this->assertEquals('1 day', $days_1);
  }

  public function testReadableHours() {
    $formatter = IntervalFormatter::readable();
    $hours_12 = $formatter(new \DateInterval('P0Y0M0DT12H0M0S'));
    $hours_1 = $formatter(new \DateInterval('P0Y0M0DT1H0M0S'));

    $this->assertEquals('12 hours', $hours_12);
    $this->assertEquals('1 hour', $hours_1);
  }

  public function testReadableYearsMonths() {
    $formatter = IntervalFormatter::readable();
    $years_2_months_6 = $formatter(new \DateInterval('P2Y6M0DT0H0M0S'));
    $years_1_months_6 = $formatter(new \DateInterval('P1Y6M0DT0H0M0S'));
    $years_1_months_1 = $formatter(new \DateInterval('P1Y1M0DT0H0M0S'));

    $this->assertEquals('2 years, 6 months', $years_2_months_6);
    $this->assertEquals('1 year, 6 months', $years_1_months_6);
    $this->assertEquals('1 year, 1 month', $years_1_months_1);
  }

  public function testReadableYearsMonthsDays() {
    $formatter = IntervalFormatter::readable();
    $years_2_months_6_days_7 = $formatter(new \DateInterval('P2Y6M7DT0H0M0S'));
    $years_1_months_6_days_7 = $formatter(new \DateInterval('P1Y6M7DT0H0M0S'));
    $years_1_months_1_days_7 = $formatter(new \DateInterval('P1Y1M7DT0H0M0S'));
    $years_1_months_1_days_1 = $formatter(new \DateInterval('P1Y1M1DT0H0M0S'));

    $this->assertEquals('2 years, 6 months, 7 days', $years_2_months_6_days_7);
    $this->assertEquals('1 year, 6 months, 7 days', $years_1_months_6_days_7);
    $this->assertEquals('1 year, 1 month, 7 days', $years_1_months_1_days_7);
    $this->assertEquals('1 year, 1 month, 1 day', $years_1_months_1_days_1);
  }

  public function testReadableSeparator() {
    $formatter_default = IntervalFormatter::readable();
    $formatter_pipe = IntervalFormatter::readable(' | ');
    $formatter_space = IntervalFormatter::readable(' ');

    $formatted_default = $formatter_default(new \DateInterval('P2Y6M7DT0H0M0S'));
    $formatted_pipe = $formatter_pipe(new \DateInterval('P2Y6M7DT0H0M0S'));
    $formatted_space = $formatter_space(new \DateInterval('P2Y6M7DT0H0M0S'));

    $this->assertEquals('2 years, 6 months, 7 days', $formatted_default);
    $this->assertEquals('2 years | 6 months | 7 days', $formatted_pipe);
    $this->assertEquals('2 years 6 months 7 days', $formatted_space);
  }

}
