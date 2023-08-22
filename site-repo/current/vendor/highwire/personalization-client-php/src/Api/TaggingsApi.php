<?php
/**
 * TaggingsApi
 * PHP version 5
 *
 * @category Class
 * @package  PersonalizationClient
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * Personalization API
 *
 * Craig's <strong>best</strong> attempt at generated API documentation for web use.
 *
 * OpenAPI spec version: 1.0.0
 * Contact: cjurney@highwire.org
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 2.4.0-SNAPSHOT
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace PersonalizationClient\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use PersonalizationClient\ApiException;
use PersonalizationClient\Configuration;
use PersonalizationClient\HeaderSelector;
use PersonalizationClient\ObjectSerializer;

/**
 * TaggingsApi Class Doc Comment
 *
 * @category Class
 * @package  PersonalizationClient
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class TaggingsApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @param ClientInterface $client
     * @param Configuration   $config
     * @param HeaderSelector  $selector
     */
    public function __construct(
        ClientInterface $client = null,
        Configuration $config = null,
        HeaderSelector $selector = null
    ) {
        $this->client = $client ?: new Client();
        $this->config = $config ?: new Configuration();
        $this->headerSelector = $selector ?: new HeaderSelector();
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation deleteTagging
     *
     * Delete tagging by Id
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  int $id id (required)
     *
     * @throws \PersonalizationClient\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return void
     */
    public function deleteTagging($context, $id)
    {
        $this->deleteTaggingWithHttpInfo($context, $id);
    }

    /**
     * Operation deleteTaggingWithHttpInfo
     *
     * Delete tagging by Id
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  int $id id (required)
     *
     * @throws \PersonalizationClient\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     */
    public function deleteTaggingWithHttpInfo($context, $id)
    {
        $returnType = '';
        $request = $this->deleteTaggingRequest($context, $id);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            return [null, $statusCode, $response->getHeaders()];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }
            throw $e;
        }
    }

    /**
     * Operation deleteTaggingAsync
     *
     * Delete tagging by Id
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  int $id id (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function deleteTaggingAsync($context, $id)
    {
        return $this->deleteTaggingAsyncWithHttpInfo($context, $id)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation deleteTaggingAsyncWithHttpInfo
     *
     * Delete tagging by Id
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  int $id id (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function deleteTaggingAsyncWithHttpInfo($context, $id)
    {
        $returnType = '';
        $request = $this->deleteTaggingRequest($context, $id);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    return [null, $response->getStatusCode(), $response->getHeaders()];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'deleteTagging'
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  int $id id (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function deleteTaggingRequest($context, $id)
    {
        // verify the required parameter 'context' is set
        if ($context === null || (is_array($context) && count($context) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $context when calling deleteTagging'
            );
        }
        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling deleteTagging'
            );
        }

        $resourcePath = '/api/{context}/tagging/{id}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // path params
        if ($context !== null) {
            $resourcePath = str_replace(
                '{' . 'context' . '}',
                ObjectSerializer::toPathValue($context),
                $resourcePath
            );
        }
        // path params
        if ($id !== null) {
            $resourcePath = str_replace(
                '{' . 'id' . '}',
                ObjectSerializer::toPathValue($id),
                $resourcePath
            );
        }

        // body params
        $_tempBody = null;

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/json', 'application/hal+json']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/json', 'application/hal+json'],
                []
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            $httpBody = $_tempBody;
            
            if($headers['Content-Type'] === 'application/json') {
                // \stdClass has no __toString(), so we should encode it manually
                if ($httpBody instanceof \stdClass) {
                    $httpBody = \GuzzleHttp\json_encode($httpBody);
                }
                // array has no __toString(), so we should encode it manually
                if(is_array($httpBody)) {
                    $httpBody = \GuzzleHttp\json_encode(ObjectSerializer::sanitizeForSerialization($httpBody));
                }
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($formParams);

            } else {
                // for HTTP post (form)
                $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
            }
        }


        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = \GuzzleHttp\Psr7\build_query($queryParams);
        return new Request(
            'DELETE',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation getTagging
     *
     * Get tagging by ID
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  int $id id (required)
     *
     * @throws \PersonalizationClient\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \PersonalizationClient\Model\TaggingResource
     */
    public function getTagging($context, $id)
    {
        list($response) = $this->getTaggingWithHttpInfo($context, $id);
        return $response;
    }

    /**
     * Operation getTaggingWithHttpInfo
     *
     * Get tagging by ID
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  int $id id (required)
     *
     * @throws \PersonalizationClient\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \PersonalizationClient\Model\TaggingResource, HTTP status code, HTTP response headers (array of strings)
     */
    public function getTaggingWithHttpInfo($context, $id)
    {
        $returnType = '\PersonalizationClient\Model\TaggingResource';
        $request = $this->getTaggingRequest($context, $id);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
                $content = $responseBody; //stream goes to serializer
            } else {
                $content = $responseBody->getContents();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\PersonalizationClient\Model\TaggingResource',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation getTaggingAsync
     *
     * Get tagging by ID
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  int $id id (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getTaggingAsync($context, $id)
    {
        return $this->getTaggingAsyncWithHttpInfo($context, $id)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getTaggingAsyncWithHttpInfo
     *
     * Get tagging by ID
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  int $id id (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getTaggingAsyncWithHttpInfo($context, $id)
    {
        $returnType = '\PersonalizationClient\Model\TaggingResource';
        $request = $this->getTaggingRequest($context, $id);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    $responseBody = $response->getBody();
                    if ($returnType === '\SplFileObject') {
                        $content = $responseBody; //stream goes to serializer
                    } else {
                        $content = $responseBody->getContents();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'getTagging'
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  int $id id (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function getTaggingRequest($context, $id)
    {
        // verify the required parameter 'context' is set
        if ($context === null || (is_array($context) && count($context) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $context when calling getTagging'
            );
        }
        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling getTagging'
            );
        }

        $resourcePath = '/api/{context}/tagging/{id}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // path params
        if ($context !== null) {
            $resourcePath = str_replace(
                '{' . 'context' . '}',
                ObjectSerializer::toPathValue($context),
                $resourcePath
            );
        }
        // path params
        if ($id !== null) {
            $resourcePath = str_replace(
                '{' . 'id' . '}',
                ObjectSerializer::toPathValue($id),
                $resourcePath
            );
        }

        // body params
        $_tempBody = null;

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/json', 'application/hal+json']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/json', 'application/hal+json'],
                []
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            $httpBody = $_tempBody;
            
            if($headers['Content-Type'] === 'application/json') {
                // \stdClass has no __toString(), so we should encode it manually
                if ($httpBody instanceof \stdClass) {
                    $httpBody = \GuzzleHttp\json_encode($httpBody);
                }
                // array has no __toString(), so we should encode it manually
                if(is_array($httpBody)) {
                    $httpBody = \GuzzleHttp\json_encode(ObjectSerializer::sanitizeForSerialization($httpBody));
                }
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($formParams);

            } else {
                // for HTTP post (form)
                $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
            }
        }


        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = \GuzzleHttp\Psr7\build_query($queryParams);
        return new Request(
            'GET',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation getTaggings
     *
     * Get all taggings for a user
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  string $email Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;cjurney@gmail.com&lt;/em&gt;. (required)
     * @param  int $page Results page you want to retrieve (0..N) (optional)
     * @param  int $size Number of records per page. (optional)
     * @param  string[] $sort Sorting criteria in the format: property(,asc|desc). Default sort order is ascending. Multiple sort criteria are supported. (optional)
     * @param  string[] $tag The tag (label). (optional)
     * @param  bool $unpaged If true, response is not paginated (but is sorted) (optional)
     *
     * @throws \PersonalizationClient\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \PersonalizationClient\Model\TaggingResources
     */
    public function getTaggings($context, $email, $page = null, $size = null, $sort = null, $tag = null, $unpaged = null)
    {
        list($response) = $this->getTaggingsWithHttpInfo($context, $email, $page, $size, $sort, $tag, $unpaged);
        return $response;
    }

    /**
     * Operation getTaggingsWithHttpInfo
     *
     * Get all taggings for a user
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  string $email Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;cjurney@gmail.com&lt;/em&gt;. (required)
     * @param  int $page Results page you want to retrieve (0..N) (optional)
     * @param  int $size Number of records per page. (optional)
     * @param  string[] $sort Sorting criteria in the format: property(,asc|desc). Default sort order is ascending. Multiple sort criteria are supported. (optional)
     * @param  string[] $tag The tag (label). (optional)
     * @param  bool $unpaged If true, response is not paginated (but is sorted) (optional)
     *
     * @throws \PersonalizationClient\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \PersonalizationClient\Model\TaggingResources, HTTP status code, HTTP response headers (array of strings)
     */
    public function getTaggingsWithHttpInfo($context, $email, $page = null, $size = null, $sort = null, $tag = null, $unpaged = null)
    {
        $returnType = '\PersonalizationClient\Model\TaggingResources';
        $request = $this->getTaggingsRequest($context, $email, $page, $size, $sort, $tag, $unpaged);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
                $content = $responseBody; //stream goes to serializer
            } else {
                $content = $responseBody->getContents();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\PersonalizationClient\Model\TaggingResources',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation getTaggingsAsync
     *
     * Get all taggings for a user
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  string $email Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;cjurney@gmail.com&lt;/em&gt;. (required)
     * @param  int $page Results page you want to retrieve (0..N) (optional)
     * @param  int $size Number of records per page. (optional)
     * @param  string[] $sort Sorting criteria in the format: property(,asc|desc). Default sort order is ascending. Multiple sort criteria are supported. (optional)
     * @param  string[] $tag The tag (label). (optional)
     * @param  bool $unpaged If true, response is not paginated (but is sorted) (optional)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getTaggingsAsync($context, $email, $page = null, $size = null, $sort = null, $tag = null, $unpaged = null)
    {
        return $this->getTaggingsAsyncWithHttpInfo($context, $email, $page, $size, $sort, $tag, $unpaged)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getTaggingsAsyncWithHttpInfo
     *
     * Get all taggings for a user
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  string $email Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;cjurney@gmail.com&lt;/em&gt;. (required)
     * @param  int $page Results page you want to retrieve (0..N) (optional)
     * @param  int $size Number of records per page. (optional)
     * @param  string[] $sort Sorting criteria in the format: property(,asc|desc). Default sort order is ascending. Multiple sort criteria are supported. (optional)
     * @param  string[] $tag The tag (label). (optional)
     * @param  bool $unpaged If true, response is not paginated (but is sorted) (optional)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getTaggingsAsyncWithHttpInfo($context, $email, $page = null, $size = null, $sort = null, $tag = null, $unpaged = null)
    {
        $returnType = '\PersonalizationClient\Model\TaggingResources';
        $request = $this->getTaggingsRequest($context, $email, $page, $size, $sort, $tag, $unpaged);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    $responseBody = $response->getBody();
                    if ($returnType === '\SplFileObject') {
                        $content = $responseBody; //stream goes to serializer
                    } else {
                        $content = $responseBody->getContents();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'getTaggings'
     *
     * @param  string $context The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. (required)
     * @param  string $email Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;cjurney@gmail.com&lt;/em&gt;. (required)
     * @param  int $page Results page you want to retrieve (0..N) (optional)
     * @param  int $size Number of records per page. (optional)
     * @param  string[] $sort Sorting criteria in the format: property(,asc|desc). Default sort order is ascending. Multiple sort criteria are supported. (optional)
     * @param  string[] $tag The tag (label). (optional)
     * @param  bool $unpaged If true, response is not paginated (but is sorted) (optional)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function getTaggingsRequest($context, $email, $page = null, $size = null, $sort = null, $tag = null, $unpaged = null)
    {
        // verify the required parameter 'context' is set
        if ($context === null || (is_array($context) && count($context) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $context when calling getTaggings'
            );
        }
        // verify the required parameter 'email' is set
        if ($email === null || (is_array($email) && count($email) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $email when calling getTaggings'
            );
        }

        $resourcePath = '/api/{context}/taggings';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        if ($email !== null) {
            $queryParams['email'] = ObjectSerializer::toQueryValue($email);
        }
        // query params
        if ($page !== null) {
            $queryParams['page'] = ObjectSerializer::toQueryValue($page);
        }
        // query params
        if ($size !== null) {
            $queryParams['size'] = ObjectSerializer::toQueryValue($size);
        }
        // query params
        if (is_array($sort)) {
            $queryParams['sort'] = $sort;
        } else
        if ($sort !== null) {
            $queryParams['sort'] = ObjectSerializer::toQueryValue($sort);
        }
        // query params
        if (is_array($tag)) {
            $queryParams['tag'] = $tag;
        } else
        if ($tag !== null) {
            $queryParams['tag'] = ObjectSerializer::toQueryValue($tag);
        }
        // query params
        if ($unpaged !== null) {
            $queryParams['unpaged'] = ObjectSerializer::toQueryValue($unpaged);
        }

        // path params
        if ($context !== null) {
            $resourcePath = str_replace(
                '{' . 'context' . '}',
                ObjectSerializer::toPathValue($context),
                $resourcePath
            );
        }

        // body params
        $_tempBody = null;

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/vnd.hw-p13n.taggings+json']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/vnd.hw-p13n.taggings+json'],
                []
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            $httpBody = $_tempBody;
            
            if($headers['Content-Type'] === 'application/json') {
                // \stdClass has no __toString(), so we should encode it manually
                if ($httpBody instanceof \stdClass) {
                    $httpBody = \GuzzleHttp\json_encode($httpBody);
                }
                // array has no __toString(), so we should encode it manually
                if(is_array($httpBody)) {
                    $httpBody = \GuzzleHttp\json_encode(ObjectSerializer::sanitizeForSerialization($httpBody));
                }
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($formParams);

            } else {
                // for HTTP post (form)
                $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
            }
        }


        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = \GuzzleHttp\Psr7\build_query($queryParams);
        return new Request(
            'GET',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        return $options;
    }
}