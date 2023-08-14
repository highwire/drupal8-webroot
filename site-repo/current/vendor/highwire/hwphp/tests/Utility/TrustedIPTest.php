<?php

use HighWire\Utility\TrustedIP;
use PHPUnit\Framework\TestCase;

class TrustedIPTest extends TestCase {
  
  public function testPrivateIP() {
    $this->assertTrue(TrustedIP::isPrivateIp('127.0.0.1'));
    $this->assertTrue(TrustedIP::isPrivateIp('10.0.0.1'));
    $this->assertFalse(TrustedIP::isPrivateIp('108.172.82.175'));
  }

  public function testTrustedIPV4() {
    $this->assertTrue(TrustedIP::isTrusted('127.0.0.1'));
    $this->assertTrue(TrustedIP::isTrusted('127.0.0.2'));
    $this->assertTrue(TrustedIP::isTrusted('10.0.0.1'));
    $this->assertTrue(TrustedIP::isTrusted('50.225.193.99')); // Los Gatos HighWire office
    $this->assertTrue(TrustedIP::isTrusted('171.66.120.1'));  // Stanford current/old public range.
    $this->assertFalse(TrustedIP::isTrusted('50.225.193.98'));
    $this->assertFalse(TrustedIP::isTrusted('108.172.82.175'));

    // Test passing additional IPs
    $this->assertTrue(TrustedIP::isTrusted('50.225.193.98', ['50.225.193.98/32', '108.172.82.175/20']));
    $this->assertTrue(TrustedIP::isTrusted('108.172.82.175', ['50.225.193.98/32', '108.172.82.175/20']));
    $this->assertFalse(TrustedIP::isTrusted('216.58.216.142', ['50.225.193.98/32', '108.172.82.175/20']));
  }

  public function testTrustedIPV6() {
    $this->assertTrue(TrustedIP::isTrusted('::1'));
    $this->assertFalse(TrustedIP::isTrusted('::2'));
    $this->assertFalse(TrustedIP::isTrusted('2001:569:bc3e:4700:8058:a232:f799:efdf'));

    // Test passing additional IPs
    $this->assertTrue(TrustedIP::isTrusted('2001:569:bc3e:4700:8058:a232:f799:efdf', ['2001:569:bc3e:4700:8058:a232:f799:efdf/128']));
    $this->assertFalse(TrustedIP::isTrusted('2607:f8b0:400a:808::200e', ['2001:569:bc3e:4700:8058:a232:f799:efdf/128']));
  }

  public function testInvalidIP() {
    $this->assertFalse(TrustedIP::isTrusted('asdfasdf'));
    $this->assertFalse(TrustedIP::isTrusted('999.999.999.999', ['999.999.999.999/32']));
  }

}
