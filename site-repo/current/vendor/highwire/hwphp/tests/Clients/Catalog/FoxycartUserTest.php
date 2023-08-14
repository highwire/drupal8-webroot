<?php

use HighWire\Clients\ClientFactory;
use HighWire\Clients\HWResponse;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class FoxycartUserTest
 */
class FoxycartUserTest extends TestCase {

  public function testFoxycartUserGet() {
    $mock = new MockHandler([
      new Response(200, [], file_get_contents(__DIR__  . '/../../assets/ecommerce/FoxyCartUserGet.xml'))
    ]);

    $handler = HandlerStack::create($mock);

//    $config['client-config']['apiToken'] = 'T9rSGzkXgL7ly4cL';
//    $config['client-config']['env'] = 'custom';
//    $config['client-config']['custom_url'] = 'https://springerbeta.ecommerce.highwire.org';
//    $foxycart = ClientFactory::get('foxycart-user', $config);

    $foxycart = ClientFactory::get('foxycart-user', ['guzzle-config' => ['handler'  => $handler], 'client-config' => ['env' => 'custom', 'custom_url' => 'https://springerbeta.ecommerce.highwire.org', 'apiToken' => 'T9rSGzkXgL7ly4cL']]);
    $foxycart_user_request = $foxycart->getFoxycartUser('ebouska@highwirepress.com');

    $foxycart_user = $foxycart_user_request->getData();

    $this->assertEquals(23498104, $foxycart_user);

  }


  public function testFoxycartUserCreate() {
    $mock = new MockHandler([
      new Response(200, [], file_get_contents(__DIR__  . '/../../assets/ecommerce/FoxyCartUserCreate.xml'))
    ]);

    $handler = HandlerStack::create($mock);

//    $config['client-config']['apiToken'] = 'T9rSGzkXgL7ly4cL';
//    $config['client-config']['env'] = 'custom';
//    $config['client-config']['custom_url'] = 'https://springerbeta.ecommerce.highwire.org';
//    $foxycart = ClientFactory::get('foxycart-user', $config);

    $foxycart = ClientFactory::get('foxycart-user', ['guzzle-config' => ['handler'  => $handler], 'client-config' => ['env' => 'custom', 'custom_url' => 'https://springerbeta.ecommerce.highwire.org', 'apiToken' => 'T9rSGzkXgL7ly4cL']]);
    $foxycart_user_request = $foxycart->createFoxycartUser('autumnbouska@gmail.com', '$S$ErHca4.RhC9R6tzIM.ZI9EAIp1CiJe9mjqE7Mx0AYZ4Ht0ZoftpE');

    $foxycart_user = $foxycart_user_request->getData();

    $this->assertEquals(23509487, $foxycart_user);

  }

  public function testFoxycartUserError() {
    $mock = new MockHandler([
      new Response(200, [], file_get_contents(__DIR__  . '/../../assets/ecommerce/FoxyCartUserError.xml'))
    ]);

    $handler = HandlerStack::create($mock);

//    $config['client-config']['apiToken'] = 'T9rSGzkXgL7ly4cL';
//    $config['client-config']['env'] = 'custom';
//    $config['client-config']['custom_url'] = 'https://springerbeta.ecommerce.highwire.org';
//    $foxycart = ClientFactory::get('foxycart-user', $config);

    $foxycart = ClientFactory::get('foxycart-user', ['guzzle-config' => ['handler'  => $handler], 'client-config' => ['env' => 'custom', 'custom_url' => 'https://springerbeta.ecommerce.highwire.org', 'apiToken' => 'T9rSGzkXgL7ly4cL']]);
    $foxycart_user_request = $foxycart->getFoxycartUser('fakeemail@gmail.com');

    $foxycart_user = $foxycart_user_request->getData();

    $this->assertEquals(FALSE, $foxycart_user);

  }

  public function testFoxycartGetUserCart() {
    $mock = new MockHandler([
      new Response(200, [], file_get_contents(__DIR__  . '/../../assets/ecommerce/FoxycartGetUserCart.json'))
    ]);

    $handler = HandlerStack::create($mock);

//    $config['client-config']['apiToken'] = 'T9rSGzkXgL7ly4cL';
//    $config['client-config']['env'] = 'custom';
//    $config['client-config']['custom_url'] = 'https://springerbeta.ecommerce.highwire.org';
//    $foxycart = ClientFactory::get('foxycart-user', $config);

    $foxycart = ClientFactory::get('foxycart-user', ['guzzle-config' => ['handler'  => $handler], 'client-config' => ['env' => 'custom', 'custom_url' => 'https://springerbeta.ecommerce.highwire.org', 'apiToken' => 'T9rSGzkXgL7ly4cL']]);
    $user_cart_request = $foxycart->getFoxycartUserCart('4f2l85drjqdshgmmecd97n0ci3');
    $user_cart_encoded = $user_cart_request->getData();
    $user_cart = json_decode(($user_cart_encoded));

    $num_items = count($user_cart->items);

    $this->assertEquals(1, $num_items);

  }

}
