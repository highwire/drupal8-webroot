<?php

use HighWire\Clients\ClientFactory;
use HighWire\Clients\HWResponse;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class catalogTest
 */
class catalogTest extends TestCase {

  public function testCatalogOffers() {
    $mock = new MockHandler([
      new Response(200, [], file_get_contents(__DIR__  . '/../../assets/ecommerce/CatalogResponseWithAncestors.json'))
    ]);

    $handler = HandlerStack::create($mock);

//    $config['client-config']['publisherId'] = 'bmjpg';
//    $catalog = ClientFactory::get('catalog', $config);

    $catalog = ClientFactory::get('catalog', ['guzzle-config' => ['handler'  => $handler], 'client-config' => ['publisherId' => 'bmjpg']]);
    $ids = array('/bjsports/51/12/949.atom', '/heartjnl/103/12/937.atom');
    $offerResponse = $catalog->getOffer($ids);

    $offers = $offerResponse->getData();
    $pricingItems = $offers->getPricingItems();
    $disposition = 'rental';

    // PricingItem 0
    $this->assertEquals('/bjsports/51/12/949.atom', $pricingItems['/bjsports/51/12/949.atom']->getId());
    $products = $pricingItems['/bjsports/51/12/949.atom']->getProducts();
    $this->assertCount(3, $products);

    // PricingItem 0 / Product 0
    $this->assertEquals('urn:doi:10.1136/bjsports-2016-097415', $products['/bjsports/51/12/949.atom']->getSku());
    $this->assertEquals('urn:atom:/bjsports/51/12/949.atom', $products['/bjsports/51/12/949.atom']->getScheme());
    $this->assertEquals('article', $products['/bjsports/51/12/949.atom']->getUnit());

    $rental_prices = $products['/bjsports/51/12/949.atom']->getPrices($disposition);
    foreach($rental_prices as $interval => $prices) {
      $interval = new \DateInterval($interval);
      $this->assertCount(3, $prices);
      $this->assertEquals('GBP', $prices['GBP']->getCurrency());
      $this->assertEquals(27.6, $prices['GBP']->getAmount());
      $this->assertEquals($interval, $prices['GBP']->getInterval());
  
      $this->assertEquals('USD', $prices['USD']->getCurrency());
      $this->assertEquals(35, $prices['USD']->getAmount());
      $this->assertEquals($interval, $prices['USD']->getInterval());
  
      $this->assertEquals('EUR', $prices['EUR']->getCurrency());
      $this->assertEquals(30, $prices['EUR']->getAmount());
      $this->assertEquals($interval, $prices['EUR']->getInterval());
    }

    // PricingItem 0 / Product 1
    $this->assertEmpty($products['/bjsports/51/12.atom']->getSku());
    $this->assertEquals('urn:atom:/bjsports/51/12.atom', $products['/bjsports/51/12.atom']->getScheme());
    $this->assertEquals('issue', $products['/bjsports/51/12.atom']->getUnit());

    $rental_prices = $products['/bjsports/51/12.atom']->getPrices($disposition);
    foreach($rental_prices as $interval => $prices) {
      $interval = new \DateInterval($interval);
      $this->assertCount(3, $prices);
      $this->assertEquals('GBP', $prices['GBP']->getCurrency());
      $this->assertEquals(100, $prices['GBP']->getAmount());
      $this->assertEquals($interval, $prices['GBP']->getInterval());
  
      $this->assertEquals('USD', $prices['USD']->getCurrency());
      $this->assertEquals(140, $prices['USD']->getAmount());
      $this->assertEquals($interval, $prices['USD']->getInterval());
  
      $this->assertEquals('EUR', $prices['EUR']->getCurrency());
      $this->assertEquals(120, $prices['EUR']->getAmount());
      $this->assertEquals($interval, $prices['EUR']->getInterval());
    }

    // PricingItem 0 / Product 2
    $this->assertEmpty($products['/bjsports/51.atom']->getSku());
    $this->assertEquals('urn:atom:/bjsports/51.atom', $products['/bjsports/51.atom']->getScheme());
    $this->assertEquals('volume', $products['/bjsports/51.atom']->getUnit());

    $rental_prices = $products['/bjsports/51.atom']->getPrices($disposition);
    foreach($rental_prices as $interval => $prices) {
      $interval = new \DateInterval($interval);
      $this->assertCount(3, $prices);
      $this->assertEquals('GBP', $prices['GBP']->getCurrency());
      $this->assertEquals(200, $prices['GBP']->getAmount());
      $this->assertEquals($interval, $prices['GBP']->getInterval());
  
      $this->assertEquals('USD', $prices['USD']->getCurrency());
      $this->assertEquals(250, $prices['USD']->getAmount());
      $this->assertEquals($interval, $prices['USD']->getInterval());
  
      $this->assertEquals('EUR', $prices['EUR']->getCurrency());
      $this->assertEquals(220, $prices['EUR']->getAmount());
      $this->assertEquals($interval, $prices['EUR']->getInterval());
    }

    // PricingItem 1
    $this->assertEquals('/heartjnl/103/12/937.atom', $pricingItems['/heartjnl/103/12/937.atom']->getId());
    $products = $pricingItems['/heartjnl/103/12/937.atom']->getProducts();
    $this->assertCount(3, $products);

    // PricingItem 1 / Product 0
    $this->assertEquals('urn:doi:10.1136/heartjnl-2015-309102', $products['/heartjnl/103/12/937.atom']->getSku());
    $this->assertEquals('urn:atom:/heartjnl/103/12/937.atom', $products['/heartjnl/103/12/937.atom']->getScheme());
    $this->assertEquals('article', $products['/heartjnl/103/12/937.atom']->getUnit());

    $rental_prices = $products['/heartjnl/103/12/937.atom']->getPrices($disposition);
    foreach($rental_prices as $interval => $prices) {
      $interval = new \DateInterval($interval);
      $this->assertCount(3, $prices);
      $this->assertEquals('GBP', $prices['GBP']->getCurrency());
      $this->assertEquals(27.6, $prices['GBP']->getAmount());
      $this->assertEquals($interval, $prices['GBP']->getInterval());
  
      $this->assertEquals('EUR', $prices['EUR']->getCurrency());
      $this->assertEquals(30, $prices['EUR']->getAmount());
      $this->assertEquals($interval, $prices['EUR']->getInterval());
  
      $this->assertEquals('USD', $prices['USD']->getCurrency());
      $this->assertEquals(35, $prices['USD']->getAmount());
      $this->assertEquals($interval, $prices['USD']->getInterval());
    }

    // PricingItem 1 / Product 1
    $this->assertEmpty($products['/heartjnl/103/12.atom']->getSku());
    $this->assertEquals('urn:atom:/heartjnl/103/12.atom', $products['/heartjnl/103/12.atom']->getScheme());
    $this->assertEquals('issue', $products['/heartjnl/103/12.atom']->getUnit());

    $rental_prices = $products['/heartjnl/103/12.atom']->getPrices($disposition);
    foreach($rental_prices as $interval => $prices) {
      $interval = new \DateInterval($interval);
      $this->assertCount(3, $prices);
      $this->assertEquals('GBP', $prices['GBP']->getCurrency());
      $this->assertEquals(100, $prices['GBP']->getAmount());
      $this->assertEquals($interval, $prices['GBP']->getInterval());
  
      $this->assertEquals('EUR', $prices['EUR']->getCurrency());
      $this->assertEquals(120, $prices['EUR']->getAmount());
      $this->assertEquals($interval, $prices['EUR']->getInterval());
  
      $this->assertEquals('USD', $prices['USD']->getCurrency());
      $this->assertEquals(140, $prices['USD']->getAmount());
      $this->assertEquals($interval, $prices['USD']->getInterval());
    }

    // PricingItem 1 / Product 2
    $this->assertEmpty($products['/heartjnl/103.atom']->getSku());
    $this->assertEquals('urn:atom:/heartjnl/103.atom', $products['/heartjnl/103.atom']->getScheme());
    $this->assertEquals('volume', $products['/heartjnl/103.atom']->getUnit());

    $rental_prices = $products['/heartjnl/103.atom']->getPrices($disposition);
    foreach($rental_prices as $interval => $prices) {
      $interval = new \DateInterval($interval);
      $this->assertCount(3, $prices);
      $this->assertEquals('USD', $prices['USD']->getCurrency());
      $this->assertEquals(250, $prices['USD']->getAmount());
      $this->assertEquals($interval, $prices['USD']->getInterval());
  
      $this->assertEquals('GBP', $prices['GBP']->getCurrency());
      $this->assertEquals(200, $prices['GBP']->getAmount());
      $this->assertEquals($interval, $prices['GBP']->getInterval());
  
      $this->assertEquals('EUR', $prices['EUR']->getCurrency());
      $this->assertEquals(220, $prices['EUR']->getAmount());
      $this->assertEquals($interval, $prices['EUR']->getInterval());
    }

    $pricingItemByApath = $offers->getPricingItemByApath('/bjsports/51/12/949.atom');
    $this->assertEquals('/bjsports/51/12/949.atom', $pricingItemByApath->getId());

    $productByApath = $pricingItemByApath->getProductByApath('/bjsports/51.atom');
    $this->assertEquals('/bjsports/51.atom', $productByApath->getId());
    $rental_prices = $productByApath->getPrices($disposition);
    foreach($rental_prices as $interval => $prices) {
      $this->assertCount(3, $prices);
    }

    $apaths = $offers->getAllApaths();

  }

  public function testCatalogDefaultCurrency() {
    $mock = new MockHandler([
      new Response(200, [], file_get_contents(__DIR__  . '/../../assets/ecommerce/defaultCurrencyGBP.json'))
    ]);

    $handler = HandlerStack::create($mock);

//    $config['client-config']['publisherId'] = 'bmjpg';
//    $catalog = ClientFactory::get('catalog', $config);

    $catalog = ClientFactory::get('catalog', ['guzzle-config' => ['handler'  => $handler], 'client-config' => ['publisherId' => 'bmjpg']]);
    $currencyResponse = $catalog->getDefaultCurrency();

    $defaultCurrency = $currencyResponse->getData();
    $this->assertEquals('GBP', $defaultCurrency);
  }

  public function testCatalogUserCurrency() {
    $mock = new MockHandler([
      new Response(200, [], file_get_contents(__DIR__  . '/../../assets/ecommerce/userCurrency.json'))
    ]);

    $handler = HandlerStack::create($mock);

//    $config['client-config']['publisherId'] = 'bmjpg';
//    $catalog = ClientFactory::get('catalog', $config);

    $catalog = ClientFactory::get('catalog', ['guzzle-config' => ['handler'  => $handler], 'client-config' => ['publisherId' => 'bmjpg']]);
    $currencyResponse = $catalog->getUserCurrency('185.182.81.9.1');

    $userCurrency = $currencyResponse->getData();
    $this->assertEquals('EUR', $userCurrency);

  }

  /**
   * @expectedException Exception
   */
  public function testCatalogBadRequest() {
    $mock = new MockHandler([
      new Response(500, [], file_get_contents(__DIR__  . '/../../assets/ecommerce/catalogBadResponse.json'))
    ]);

    $handler = HandlerStack::create($mock);

    $config['client-config']['publisherId'] = 'bmjpg';
    $catalog = ClientFactory::get('catalog', $config);

//    $catalog = ClientFactory::get('catalog', ['client-config' => ['publisherId' => 'bmjpg']]);
    $catalog = ClientFactory::get('catalog', ['guzzle-config' => ['handler'  => $handler], 'client-config' => ['publisherId' => 'bmjpg']]);
    $ids = array('/bjsports/51/12/949void.atom');
    $offerResponse = $catalog->getOffer($ids, FALSE , FALSE, TRUE);

    $offers = $offerResponse->getData();
    $pricingItems = $offers->getPricingItems();
  }

  /**
   * @expectedException Exception
   */
  public function testCatalogBadUserCurrency() {
    $mock = new MockHandler([
      new Response(500, [], file_get_contents(__DIR__  . '/../../assets/ecommerce/catalogBadUserCurrencyResponse.json'))
    ]);

    $handler = HandlerStack::create($mock);

    //    $config['client-config']['publisherId'] = 'bmjpg';
    //    $catalog = ClientFactory::get('catalog', $config);

    $catalog = ClientFactory::get('catalog', ['guzzle-config' => ['handler'  => $handler], 'client-config' => ['publisherId' => 'xxxxx']]);
//    $catalog = ClientFactory::get('catalog', ['client-config' => ['publisherId' => 'xxxxx']]);
    $currencyResponse = $catalog->getUserCurrency('185.182.81.9.1');

    $userCurrency = $currencyResponse->getData();
    $this->assertEquals('EUR', $userCurrency);

  }

}
