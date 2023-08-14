<?php

use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class StarLoggerTest extends TestCase {

  public function testhandleStarRequestAsync() {

    $mock = new MockHandler([
        new Response(200, [], file_get_contents(__DIR__  . '/star.logger.json'))
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('star-logger', [], 'development');

    $star_request_string = 'session=A8221B476736B73FF8207D18878BEA29&ua=Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:67.0) Gecko/20100101 Firefox/67.0&platformapikey=7f33c2d9e7363412&customerid=123123123&title=AccessEngineering&doi=&uuid=ecdf25e4-35dd-4086-a59a-f6a37b2788d0&seqid=4&reportingdate=1559580906347&referer=scolaris-mheaeworks-dev-ladams.fr-freebird-web-dev-01.highwire.org&consortium=+15216&datatype=BOOK&desc=Article&proprietaryid=All AccessScience Content Retrievals&itemtype=SECTIONED_HTML&isbn=9780071422895&publisher=McGraw-Hill Education';

    $result = $client->sendStarRequest($star_request_string, ['timeout' => 300]);

    $status_code = $result->getStatusCode();
    $this->assertEquals(200, $status_code);
  }

}
