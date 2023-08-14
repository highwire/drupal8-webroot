<?php

namespace HighWire\Clients\AtomX;

use Elasticsearch\ClientBuilder;
use HighWire\PayloadFetcherInterface;
use HighWire\Utility\Apath;
use HighWire\FreebirdSchema\SchemaServiceInterface;
use HighWire\Parser\ExtractPolicy\ExtractPolicy;
use HighWire\Parser\ExtractPolicy\Field as ExtractPolicyField;
use HighWire\FreebirdSchema\Schema;
use HighWire\FreebirdSchema\ItemType;
use HighWire\FreebirdSchema\Field;
use HighWire\Exception\HighWirePayloadNotFoundException;
use HighWire\Exception\HighWireFreebirdSchemaNotFound;
use Elasticsearch\Client as ElasticClient;
use Elasticsearch\Common\Exceptions\Missing404Exception;

/**
 * AtomX Client.
 */
class AtomX implements PayloadFetcherInterface, SchemaServiceInterface {

  protected $indexes = [];
  protected $policy;
  protected $policyName;
  protected $primaryField;
  protected $corpusField;
  protected $typeField;
  protected $policyFields;
  protected $client;
  protected $result;
  protected $search;
  protected $response;

  /**
   * AtomX Constructor.
   *
   * @param array $hosts
   *   An array of host uri's.
   * @param array $config
   *   Configuration array, options:
   *     [
   *       // Elasticsearch use guzzle ringPHP library for making
   *       // requests. Most guzzle http client option apply.
   *       // @see https://github.com/guzzle/RingPHP/blob/master/src/Client/CurlFactory.php#L352
   *       // for the options that do.
   *       'client-options' => [],
   *       // Any additional settings atomx needs to function.
   *       // Nothing at this time.
   *       'logger' => Psr\Log\LoggerInterface,
   *     ].
   */
  public function __construct(array $hosts, array $config = []) {
    // Load the client.
    $builder = ClientBuilder::create();
    $builder->setHosts($hosts);
    $builder->setSelector('\Elasticsearch\ConnectionPool\Selectors\StickyRoundRobinSelector');
    $client_options = !empty($config['client-options']) ? $config['client-options'] : [];
    $builder->setConnectionParams(['client' => $client_options]);

    if (!empty($config['logger'])) {
      $builder->setLogger($config['logger']);
    }

    $this->setClient($builder->build());
  }

  /**
   * Set the Corpora against which queries will be run.
   *
   * @param string[] $corpora
   *   Array of corpus codes. For example: ['bmj', 'sci'].
   */
  public function setCorpora(array $corpora) {
    $indexes = [];

    foreach ($corpora as $corpus) {
      $indexes[] = $this->policyName . ':' . $corpus;
    }

    $this->setIndexes($indexes);
  }

  /**
   * {@inheritdoc}
   *
   * @see \HighWire\DataFetcher::get()
   */
  public function get($id, $policy_name = NULL): array {
    if ($items = $this->getMultiple([$id], $policy_name)) {
      return reset($items);
    }

    return [];
  }

  /**
   * {@inheritdoc}
   *
   * @see \HighWire\DataFetcher::getMultiple()
   */
  public function getMultiple(array $ids, $policy_name = NULL): array {
    $results = [];

    if (empty($policy_name)) {
      $policy_name = $this->policyName;
    }

    // Do one query per corpus
    $corpora = Apath::sortByCorpora($ids);
    foreach ($corpora as $corpus => $ids) {
      $body = ['docs' => []];
      foreach ($ids as $id) {
        $body['docs'][] = ['_id' => $id];
      }
      $params = [
        'index' => $policy_name . ':' . $corpus,
        'body' => $body,
      ];

      $this->response = $this->getClient()->mget($params);

      if (empty($this->response['docs'])) {
        throw new HighWirePayloadNotFoundException("No payload found for id " . implode(", ", $ids) . " and extract-policy $policy_name");
      }

      foreach ($this->response['docs'] as $doc) {
        if (array_key_exists('_source', $doc)) {
          $results[$doc['_id']] = $doc['_source'];
        }
      }
    }

    return $results;
  }

  /**
   * {@inheritdoc}
   *
   * @see \HighWire\PayloadFetcherInterface::getCorpusIds()
   */
  public function getCorpusIds($corpus, $policy_name = NULL) {
    if (empty($policy_name)) {
      $policy_name = $this->policyName;
    }
    if (empty($policy_name)) {
      throw new \Exception("cannot find policy_name for call to AtomX:: getCorpusIds()");
    }

    $params = [
      'index' => $policy_name . ':' . $corpus,
      'scroll' => '30s', // scroll timeout, all scrolling must happen within this timeframe
      'size' => 5000,
      'body' => [
        '_source' => FALSE,
      ],
    ];

    $this->response = $this->getClient()->search($params);

    $ids = [];

    // Now we loop until the scroll "cursors" are exhausted
    while (isset($this->response['hits']['hits']) && count($this->response['hits']['hits']) > 0) {
      foreach ($this->response['hits']['hits'] as $doc) {
        $ids[] = $doc['_id'];
      }

      // When done, get the new scroll_id to continue scrolling
      $scroll_id = $this->response['_scroll_id'];

      // Execute a another scroll request and repeat
      $this->response = $this->getClient()->scroll([
        "scroll_id" => $scroll_id,
        "scroll" => "30s"
      ]);
    }

    return $ids;
  }

  /**
   * Get the indexes.
   *
   * @return array
   *   An array of indexes.
   */
  public function getIndexes(): array {
    return $this->indexes;
  }

  /**
   * Query using key->value pairs.
   *
   * @param array $params
   *   Key-value pairs to match.
   *   The keys can include subpaths to query
   *   into sub-structures. (eg `journal-meta.journal-title`)
   * @param int $page
   *   Optional. If there are more results
   *   than the $max_results, start on this page.
   * @param int $max_results
   *   Optional. Maximum results per page.
   *
   * @return array
   *   An array of results.
   */
  public function query(array $params, $page = 0, $max_results = 1000): array {
    $filter = [];
    foreach ($params as $key => $val) {
      $filter[] = [
        "term" => [$key => $val],
      ];
    }
    $search_query = [
      "size" => $max_results,
      "query" => [
        "bool" => [
          "filter" => $filter,
        ],
      ],
    ];

    return $this->search($search_query);
  }

  /**
   * Query using key->value pairs, only return a single item.
   *
   * @param array $params
   *   Key-value pairs to match.
   *   The keys can include subpaths to query
   *   into sub-structures. (eg `journal-meta.journal-title`)
   *
   * @return array
   *   A result array.
   */
  public function querySingle(array $params): array {
    $results = $this->query($params, 0, 1);
    if (empty($results)) {
      return [];
    }
    else {
      return reset($results);
    }
  }

  /**
   * Given an elasticsearch search array, perform a search and return items.
   *
   * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl.html
   *
   * @param array|string $search_query
   *   An array representation of a elasticsearch search or a raw json search string.
   *
   * @return array
   *   An array of search results.
   */
  public function search($search_query): array {
    if (empty($this->indexes)) {
      throw new \Exception("No indexes defines. Please define an index to search on using Elastic->setCorpora(['corpus1','corpus2']).");
    }
    $this->search = [
      'index' => implode(',', $this->indexes),
      'body' => $search_query,
    ];

    $this->response = $this->getClient()->search($this->search);

    return $this->getResults();
  }

  /**
   * Get the full number of matched items from the previous query or search.
   *
   * @return int
   *   Number of results.
   */
  public function numResults() {
    return $this->response['hits']['total'];
  }

  /**
   * Get the raw client for doing full elasticsearch queries.
   *
   * Documentation on how to use the raw elasticsearch client:
   *  - https://github.com/elastic/elasticsearch-php
   *  - https://www.elastic.co/guide/en/elasticsearch/client/php-api/5.0/index.html.
   *
   * @return \Elasticsearch\Client
   *   The elasticsearch client.
   */
  public function getClient(): ElasticClient {
    return $this->client;
  }

  /**
   * Set the client.
   *
   * @param \Elasticsearch\Client $client
   *   The elastic search connected client.
   */
  public function setClient(ElasticClient $client) {
    $this->client = $client;
  }

  /**
   * Set the indexes.
   *
   * @param array $indexes
   *   List of idexes to query.
   */
  public function setIndexes(array $indexes) {
    $this->indexes = $indexes;
  }

  /**
   * Set the ExtractPolicy used by this AtomX client for indexing.
   *
   * @param \HighWire\Parser\ExtractPolicy\ExtractPolicy $extract_policy
   *   An ExtractPolicy object.
   *
   * @see policy()
   */
  public function setPolicy(ExtractPolicy $extract_policy) {
    $this->policy = $extract_policy;
    $this->policyName = $extract_policy->getPolicyId();
    $this->primaryField = $this->policy->getPrimaryIdField();
    $this->corpusField = $this->policy->getCorpusField();
    $this->typeField = $this->policy->getTypeField();
    $this->policyFields = $this->policy->fields();
  }

  /**
   * {@inheritdoc}
   *
   * @see \HighWire\FreebirdSchema\SchemaServiceInterface::getFreebirdSchema()
   */
  public function getFreebirdSchema(ExtractPolicy $extract_policy, array $corpora): Schema {
    // A freebird schema in this context is a
    // mix of the extract policy and the elastic schema.
    $this->setPolicy($extract_policy);
    $this->setCorpora($corpora);
    try {
      $mappings = $this->getMapping();
    }
    catch (Missing404Exception $e) {
      throw new HighWireFreebirdSchemaNotFound("Missing elastic mapping for " . implode(', ', $this->getIndexes()));
    }

    $schema = new Schema($this->policyName);

    foreach ($mappings as $type_name => $type) {
      $item_type = new ItemType($type_name);
      if ($properties = $type->getProperties()) {
        foreach ($properties as $property_name => $property) {
          if (empty($this->policy->fieldExists($property_name))) {
            // If we are missing the extact
            // policy definition it could be that the
            // extract policy was updates and
            // the index is not yet up to date. Just
            // skip it for now.
            continue;
          }

          if ($field = $this->createFreebirdField($property, $this->policyFields[$property->getName()])) {
            $item_type->addField($field);
          }
        }
      }

      $schema->addItemType($item_type);
    }

    return $schema;
  }

  /**
   * Helper recursive method for creating
   * a freebird field object with child fields.
   *
   * @todo deal with a mismatch between elastic fields and the policy fields
   *
   * @return \HighWire\FreebirdSchema\Field
   *   Rerturns a freebird schema field object.
   */
  /**
   * Helper recursive method for creating
   * a freebird field object with child fields.
   *
   * @param \HighWire\Clients\AtomX\Property $property
   *   An atomx property object.
   * @param \HighWire\Parser\ExtractPolicy\Field $policy_field
   *   A policy field object from an extract_policy.
   * @param \HighWire\FreebirdSchema\Field $parent_field
   *   A freebird field object.
   *
   * @return \HighWire\FreebirdSchema\Field
   *   A freebird field object.
   */
  protected function createFreebirdField(Property $property, ExtractPolicyField $policy_field, Field $parent_field = NULL): Field {
    $field = NULL;
    if (!empty($policy_field->type())) {
      $field = new Field($policy_field->type(), $property->getName());

      if (!empty($policy_field->description())) {
        $field->setDescription($policy_field->description());
      }

      if (!empty($policy_field->label())) {
        $field->setLabel($policy_field->label());
      }

      if ($policy_field->list()) {
        $field->setIsMultiple(TRUE);
      }

      if (!empty($policy_field->drupalType())) {
        $field->setDrupalType($policy_field->drupalType());
      }

      if ($attributes = $policy_field->getAttributes()) {
        $field->setAttributes($attributes);
      }

      if ($property->hasChildProperties()) {
        $child_fields = $policy_field->structure();
        foreach ($property->getChildProperties() as $child_property) {
          if (!empty($child_fields[$child_property->getName()])) {
            $child_property_field = $child_fields[$child_property->getName()];
            if ($child_field = $this->createFreebirdField($child_property, $child_property_field, $field)) {
              $field->addChildField($child_field);
            }
          }
        }
      }

      if (!empty($parent_field)) {
        $parent_field->addChildField($field);
      }
    }

    return $field;
  }

  /**
   * Get the elastic mapping.
   *
   * @return array
   *   The elasticsearch mapping/schema.
   */
  public function getMapping(): array {
    $params = [
      'index' => implode(",", $this->indexes),
    ];

    $response = $this->getClient()->indices()->getMapping($params);

    $types = [];
    foreach ($response as $index) {
      foreach ($index['mappings'] as $content_type => $mapping) {
        if ($content_type == '_default_') {
          continue;
        }
        // Indexes share content types, make sure we don't add it twice.
        if (empty($types[$content_type])) {
          $types[$content_type] = new Type($content_type);
        }
        if (!empty($mapping['properties'])) {
          foreach ($mapping['properties'] as $name => $data) {
            // Make sure we don't add the same field twice.
            if (!empty($types[$content_type]->getPropertyByName($name))) {
              continue;
            }

            $property = new Property($name, $data);
            $types[$content_type]->addProperty($property);
          }
        }
      }
    }

    // Sort so types aren't returned in a random order.
    ksort($types);

    return $types;
  }

  /**
   * Transform elasticsearch results into array of items.
   *
   * @return array
   *   Return an array of results from the elasticsearch cluster.
   */
  protected function getResults(): array {
    $results = [];
    if (empty($this->response['hits']['total']) || $this->response['hits']['total'] == 0) {
      return $results;
    }
    else {
      foreach ($this->response['hits']['hits'] as $hit) {
        $results[$hit['_id']] = $hit['_source'] ?? $hit['_id'];
      }
      return $results;
    }
  }

}
