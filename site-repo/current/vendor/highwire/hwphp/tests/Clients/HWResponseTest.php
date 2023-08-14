<?php

use HighWire\Clients\HWResponse;
use GuzzleHttp\Psr7\Stream;
use PHPUnit\Framework\TestCase;

class HWResponseTest extends TestCase {
  public function testResponse() {
    $data = 'test';
    $psr_response = new GuzzleHttp\Psr7\Response(200, ['some-header' => 'some-value'], $data);
    $response = new HWResponse($psr_response, $data);
    $this->assertEquals($data, $response->getData());
    $this->assertEquals('some-value', $response->getHeader('some-header')[0]);
    $this->assertEquals('test', $response->getBody());
    $new_response = $response->withAddedHeader('some-header', 'some-new-value');
    $this->assertEquals('some-new-value', $new_response->getHeader('some-header')[1]);
    $new_response = $response->withoutHeader('some-header');
    $this->assertEmpty($new_response->getHeader('some-header'));
    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals('1.1', $response->getProtocolVersion());
    $new_response = $response->withProtocolVersion('1.0');
    $this->assertEquals('1.0', $new_response->getProtocolVersion());
    $new_response = $response->withHeader('X-Some-Header', 'some header value');
    $this->assertEquals('some header value', $new_response->getHeader('X-Some-Header')[0]);
    $new_response = $response->withBody(new Stream(fopen('data://text/plain;base64,' . base64_encode('new body'), 'r')));
    $this->assertEquals('new body', $new_response->getBody());
    $new_response = $response->withStatus(500, 'some error');
    $this->assertEquals(500, $new_response->getStatusCode());
    $response->setData('new data');
    $this->assertEquals('new data', $response->getData());
  }
}
