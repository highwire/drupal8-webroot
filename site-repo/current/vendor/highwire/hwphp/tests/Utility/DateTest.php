<?php

use HighWire\Utility\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase {

  public function testDetermineFormat() {
    $this->assertEquals(Date::DATE_TYPE_XML, Date::determineFormat("<atom:updated>2017-09-18T15:14:29.844-07:00</atom:updated>"));
    $this->assertEquals(Date::DATE_TYPE_ISO8601, Date::determineFormat("2017-09-29"));
    $this->assertEquals(Date::DATE_TYPE_ISO8601, Date::determineFormat("2017-09-29T14:30:18+00:00"));
    $this->assertEquals(Date::DATE_TYPE_ISO8601, Date::determineFormat("2017-09-29T14:30:18Z"));
    $this->assertEquals(Date::DATE_TYPE_ISO8601, Date::determineFormat("2017-W39"));
    $this->assertEquals(Date::DATE_TYPE_ISO8601, Date::determineFormat("2017-W39"));
    $this->assertEquals(Date::DATE_TYPE_TIMESTAMP, Date::determineFormat("1506696674"));
    $this->assertEquals(Date::DATE_TYPE_UNKOWN, Date::determineFormat("tickety boo"));
  }

  public function testParseDate() {
    $this->assertEquals(get_class(Date::parseDate("2017-09-29")), \DateTime::class);
    $this->assertEquals(get_class(Date::parseDate("1506696674")), \DateTime::class);
    $this->assertNull(Date::parseDate('gobbly gook', 'some format'));
  }

  /**
   * @expectedException Exception
   */
  public function testParseException() {
    $this->assertEquals(get_class(Date::parseDate("tickety boo")), \DateTime::class);
  }

  public function parseUnixTimestamp() {
    $this->assertEquals(get_class(Date::parseUnixTimestamp("1506696674")), \DateTime::class);
    $this->assertNull(Date::parseUnixTimestamp('not a unix time stamp'));
  }

  public function parseParseISO8601() {
    $this->assertEquals(get_class(Date::parseISO8601("2017-09-29")), \DateTime::class);
    $this->assertEquals(get_class(Date::parseISO8601("2017-02-06T14:25:31.395-08:00")), \DateTime::class);
    $this->assertNull(Date::parseISO8601('not a date'));
  }

}
