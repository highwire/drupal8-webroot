<?php

use HighWire\Clients\Catalog\Offer;
use PHPUnit\Framework\TestCase;

/**
 * Class offersTest
 */
class offersTest extends TestCase {

  public function testOffersMultipleResponseWithParents() {
    $raw_response = file_get_contents(__DIR__ . '/../../assets/ecommerce/MultipleResponseWithParents.json');
    $responses = json_decode($raw_response, TRUE);

    $offers = new Offer($responses);

    $pricing_items = $offers->getPricingItems();

    // Confirm the Offer id.
    $this->assertEquals('/bjsports/51/12/949.atom', $pricing_items['/bjsports/51/12/949.atom']->getId());

    // Confirm the offer has the right number of pricing items.
    $products = $pricing_items['/bjsports/51/12/949.atom']->getProducts();
    $this->assertCount(3, $products);

    // Products
    $this->assertInstanceOf('HighWire\Clients\Catalog\Product', $products['/bjsports/51/12/949.atom']);
    $this->assertInstanceOf('HighWire\Clients\Catalog\Product', $products['/bjsports/51/12.atom']);
    $this->assertInstanceOf('HighWire\Clients\Catalog\Product', $products['/bjsports/51.atom']);

    // Product /bjsports/51/12/949.atom
    $this->assertEquals('/bjsports/51/12/949.atom', $products['/bjsports/51/12/949.atom']->getId());
    $this->assertEquals('urn:doi:10.1136/bjsports-2016-097415', $products['/bjsports/51/12/949.atom']->getSku());
    $this->assertEquals('urn:atom:/bjsports/51/12/949.atom', $products['/bjsports/51/12/949.atom']->getScheme());
    $this->assertEquals('article', $products['/bjsports/51/12/949.atom']->getUnit());

    // Prices
    $rental_prices = $products['/bjsports/51/12/949.atom']->getPrices('rental');
    foreach($rental_prices as $interval => $prices) {
      switch($interval) {
        case 24:
          $test_amounts = [
            'GBP' => 27.6,
            'USD' => 35,
            'EUR' => 30,
          ];
          break;

        case 48:
          $test_amounts = [
            'GBP' => 41.4,
            'USD' => 52.5,
            'EUR' => 45,
          ];
          break;

        case 72:
          $test_amounts = [
            'GBP' => 55.2,
            'USD' => 70,
            'EUR' => 60,
          ];
          break;

        default:
          $test_amounts = [];
          break;
      }

      foreach($test_amounts as $currency => $amount) {
        $this->assertInstanceOf('HighWire\Clients\Catalog\Price', $prices[$currency]);
        $this->assertEquals($currency, $prices[$currency]->getCurrency());
        $this->assertEquals($amount, $prices[$currency]->getAmount());
        $this->assertEquals($interval, $prices[$currency]->getInterval());
      }
    }
  }
}