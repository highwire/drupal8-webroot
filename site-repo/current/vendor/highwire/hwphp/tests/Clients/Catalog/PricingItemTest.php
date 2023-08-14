<?php

use HighWire\Clients\Catalog\PricingItem;
use HighWire\Clients\Catalog\Offer;
use PHPUnit\Framework\TestCase;

/**
 * Class PricingTest
 */
class PricingItemTest extends TestCase {

  public function testPricingItemDefault() {
    $pricingItem = new PricingItem([]);
    $this->assertInstanceOf(PricingItem::class, $pricingItem);
  }

}