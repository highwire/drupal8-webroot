# PersonalizationClient\TaggingsApi

All URIs are relative to *https://104.232.16.4/personalization*

Method | HTTP request | Description
------------- | ------------- | -------------
[**deleteTagging**](TaggingsApi.md#deleteTagging) | **DELETE** /api/{context}/tagging/{id} | Delete tagging by Id
[**getTagging**](TaggingsApi.md#getTagging) | **GET** /api/{context}/tagging/{id} | Get tagging by ID
[**getTaggings**](TaggingsApi.md#getTaggings) | **GET** /api/{context}/taggings | Get all taggings for a user


# **deleteTagging**
> deleteTagging($context, $id)

Delete tagging by Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\TaggingsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 388; // int | id

try {
    $apiInstance->deleteTagging($context, $id);
} catch (Exception $e) {
    echo 'Exception when calling TaggingsApi->deleteTagging: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **id** | **int**| id |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json, application/hal+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTagging**
> \PersonalizationClient\Model\TaggingResource getTagging($context, $id)

Get tagging by ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\TaggingsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 789; // int | id

try {
    $result = $apiInstance->getTagging($context, $id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TaggingsApi->getTagging: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **id** | **int**| id |

### Return type

[**\PersonalizationClient\Model\TaggingResource**](../Model/TaggingResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json, application/hal+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTaggings**
> \PersonalizationClient\Model\TaggingResources getTaggings($context, $email, $page, $size, $sort, $tag, $unpaged)

Get all taggings for a user

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\TaggingsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$email = "\"cjurney@gmail.com\""; // string | Authenticated user's email address as provided by the calling application, eg, <em>cjurney@gmail.com</em>.
$page = 56; // int | Results page you want to retrieve (0..N)
$size = 56; // int | Number of records per page.
$sort = array("sort_example"); // string[] | Sorting criteria in the format: property(,asc|desc). Default sort order is ascending. Multiple sort criteria are supported.
$tag = array("tag_example"); // string[] | The tag (label).
$unpaged = true; // bool | If true, response is not paginated (but is sorted)

try {
    $result = $apiInstance->getTaggings($context, $email, $page, $size, $sort, $tag, $unpaged);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TaggingsApi->getTaggings: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **email** | **string**| Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;cjurney@gmail.com&lt;/em&gt;. |
 **page** | **int**| Results page you want to retrieve (0..N) | [optional]
 **size** | **int**| Number of records per page. | [optional]
 **sort** | [**string[]**](../Model/string.md)| Sorting criteria in the format: property(,asc|desc). Default sort order is ascending. Multiple sort criteria are supported. | [optional]
 **tag** | [**string[]**](../Model/string.md)| The tag (label). | [optional]
 **unpaged** | **bool**| If true, response is not paginated (but is sorted) | [optional]

### Return type

[**\PersonalizationClient\Model\TaggingResources**](../Model/TaggingResources.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/vnd.hw-p13n.taggings+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

