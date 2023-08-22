<?php

namespace HighWire\Clients\UsageStats;

use HighWire\Clients\Client;
use HighWire\Clients\HWResponse;
use GuzzleHttp\Promise\Promise;
use BetterDomDocument\DOMDoc;

/**
 * usage-stats.highwire.org Client
 */
class UsageStats extends Client {

  /**
   * Get usage-stats for an article, by month.
   *
   * @param string $apath
   *   Atom path for an article.
   * @param bool $combine
   *   Should highwire and PMC article stats be combined?
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise, result has array of apaths.
   */
  public function singleArticleAsync(string $apath, bool $combine = TRUE): Promise {
    $combine = $combine ? 'true' : 'false';
    return $this->processUsageStatsRequest('singlearticle' . $apath . '?combine=' . $combine);
  }

  /**
   * Get usage-stats for an article, by day, for an entire year.
   *
   * @param string $apath
   *   Atom path for an article.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise, result has array of apaths.
   */
  public function singleArticleYearAsync(string $apath): Promise {
    return $this->processUsageStatsRequest('singlearticleyear' . $apath);
  }

  /**
   * UsageStats requests are nearly identical across different endpoints.
   * 
   * We replicate common behavior here.
   *
   * @param string $uri
   *   UsageStatus URI.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise.
   */
  private function processUsageStatsRequest(string $uri): Promise {
    $request = $this->buildRequest('GET', $uri);

    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $dom_doc = new DomDoc(strval($resp->getBody()));

      $result = [];
      $rows = $dom_doc->xpath("//results:row");
      if (!empty($rows)) {
        foreach ($rows as $row) {
          $res = $this->resultFromRow($dom_doc, $row);
          if ($res) {
            $result[] = $res;
          }
        }
      }

      $hw_response = new HWResponse($resp, $result);
      $promise->resolve($hw_response);
    });

    return $promise;
  }


  /**
   * Given a <results:row> element, process it into a usage result.
   *
   * @param \BetterDomDocument\DOMDoc $dom_doc
   *   The DOMDoc object.
   * @param \DOMElement $row
   *   The <results:row> element.
   *
   * @return array|false
   *   A results array, or false if not valid.
   */
  private function resultFromRow(DOMDoc $dom_doc, \DOMElement $row): array {
    $date_elem = $dom_doc->xpathSingle("results:use_month", $row);
    if (!$date_elem || empty($date_elem->nodeValue)) {
      $date_elem = $dom_doc->xpathSingle("results:use_date", $row);
      if (!$date_elem || empty($date_elem->nodeValue)) {
        return FALSE;
      }
    }

    $result = [];

    // Date
    if (strlen($date_elem->nodeValue) >= 4) {
      $result['year'] = substr($date_elem->nodeValue, 0, 4);
    }
    if (strlen($date_elem->nodeValue) >= 6) {
      $result['month'] = substr($date_elem->nodeValue, 4, 2);
    }
    if (strlen($date_elem->nodeValue) >= 8) {
      $result['day'] = substr($date_elem->nodeValue, 6, 2);
    }

    // Stats
    foreach (['abstract', 'full', 'full_text', 'pdf', 'powerpoint'] as $field) {
      $count_elem = $dom_doc->xpathSingle("results:" . $field, $row);
      if (!$count_elem || $count_elem->nodeValue == '') {
        continue;
      }
      $count_value = intval($count_elem->nodeValue);
      $key = ($field == 'full_text') ? 'full' : $field; // WHY IS THERE 'full' AND 'full_text' ? ಠ_ಠ
      $result[$key] = $count_value;
    }

    // Platform
    $platform_elem = $dom_doc->xpathSingle("results:platform", $row);
    if ($platform_elem && !empty($platform_elem->nodeValue)) {
      $result['platform'] = $platform_elem->nodeValue;
    }
    else {
      // Platform missing means it's 'combined highwire + PMC'.
      $result['platform'] = 'highwire-pmc';
    }

    return $result;
  }

}
