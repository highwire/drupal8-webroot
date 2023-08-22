# PersonalizationClient\TagsApi

All URIs are relative to *https://104.232.16.4/personalization*

Method | HTTP request | Description
------------- | ------------- | -------------
[**createTag**](TagsApi.md#createTag) | **POST** /api/{context}/tags | Create a tag, optionally with content to tag
[**deleteTag**](TagsApi.md#deleteTag) | **DELETE** /api/{context}/tags/{id} | Delete a tag
[**getTag**](TagsApi.md#getTag) | **GET** /api/{context}/tags/{id} | Get single Tag by its ID
[**getTagCounts**](TagsApi.md#getTagCounts) | **GET** /api/{context}/tag-counts | Get tag-counts for user
[**getTags**](TagsApi.md#getTags) | **GET** /api/{context}/tags | Get all tags for the specified user
[**updateTag**](TagsApi.md#updateTag) | **PATCH** /api/{context}/tags/{id} | Update existing tag


# **createTag**
> \PersonalizationClient\Model\TagResource createTag($context, $tag_data)

Create a tag, optionally with content to tag

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\TagsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$tag_data = new \PersonalizationClient\Model\TagData(); // \PersonalizationClient\Model\TagData | tagData

try {
    $result = $apiInstance->createTag($context, $tag_data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TagsApi->createTag: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **tag_data** | [**\PersonalizationClient\Model\TagData**](../Model/TagData.md)| tagData |

### Return type

[**\PersonalizationClient\Model\TagResource**](../Model/TagResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json, application/vnd.hw-p13n.tag-data+json
 - **Accept**: application/json, application/hal+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteTag**
> deleteTag($context, $id)

Delete a tag

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\TagsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 388; // int | id

try {
    $apiInstance->deleteTag($context, $id);
} catch (Exception $e) {
    echo 'Exception when calling TagsApi->deleteTag: ', $e->getMessage(), PHP_EOL;
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

# **getTag**
> \PersonalizationClient\Model\TagResource getTag($context, $id, $with_descendant_taggings, $with_descendants, $with_feature, $with_owner, $with_taggings)

Get single Tag by its ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\TagsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 33; // int | Tag ID
$with_descendant_taggings = "yes"; // bool | withDescendantTaggings
$with_descendants = "yes"; // bool | withDescendants
$with_feature = "yes"; // bool | withFeature
$with_owner = "yes"; // bool | withOwner
$with_taggings = "yes"; // bool | withTaggings

try {
    $result = $apiInstance->getTag($context, $id, $with_descendant_taggings, $with_descendants, $with_feature, $with_owner, $with_taggings);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TagsApi->getTag: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **id** | **int**| Tag ID |
 **with_descendant_taggings** | **bool**| withDescendantTaggings | [optional]
 **with_descendants** | **bool**| withDescendants | [optional]
 **with_feature** | **bool**| withFeature | [optional]
 **with_owner** | **bool**| withOwner | [optional]
 **with_taggings** | **bool**| withTaggings | [optional]

### Return type

[**\PersonalizationClient\Model\TagResource**](../Model/TagResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json, application/vnd.hw-p13n.tag+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTagCounts**
> \PersonalizationClient\Model\TagCountResource getTagCounts($context, $email)

Get tag-counts for user

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\TagsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$email = "\"pookie@gmail.com\""; // string | Authenticated user's email address as provided by the calling application.

try {
    $result = $apiInstance->getTagCounts($context, $email);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TagsApi->getTagCounts: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **email** | **string**| Authenticated user&#39;s email address as provided by the calling application. |

### Return type

[**\PersonalizationClient\Model\TagCountResource**](../Model/TagCountResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTags**
> \PersonalizationClient\Model\TagResources getTags($context, $email, $content, $content_type, $page, $size, $sort, $tag, $unpaged, $with_available, $with_feature, $with_workspace)

Get all tags for the specified user

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\TagsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$email = "\"cjurney@gmail.com\""; // string | Authenticated user's email address as provided by the calling application, eg, <em>cjurney@gmail.com</em>.
$content = "\"/sgrwfccn/12/2/48.atom\""; // string | The resource that is tagged, eg, an Atom.
$content_type = "\"article\""; // string | The type of Atom that is marked, eg, article, chapter, figure or the like.
$page = 56; // int | Results page you want to retrieve (0..N)
$size = 56; // int | Number of records per page.
$sort = array("sort_example"); // string[] | Sorting criteria in the format: property(,asc|desc). Default sort order is ascending. Multiple sort criteria are supported.
$tag = "\"sports\""; // string | The tag (label).
$unpaged = true; // bool | If true, response is not paginated (but is sorted)
$with_available = "yes"; // bool | with-available
$with_feature = "yes"; // bool | with-feature
$with_workspace = "yes"; // bool | with-workspace

try {
    $result = $apiInstance->getTags($context, $email, $content, $content_type, $page, $size, $sort, $tag, $unpaged, $with_available, $with_feature, $with_workspace);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TagsApi->getTags: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **email** | **string**| Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;cjurney@gmail.com&lt;/em&gt;. |
 **content** | **string**| The resource that is tagged, eg, an Atom. | [optional]
 **content_type** | **string**| The type of Atom that is marked, eg, article, chapter, figure or the like. | [optional]
 **page** | **int**| Results page you want to retrieve (0..N) | [optional]
 **size** | **int**| Number of records per page. | [optional]
 **sort** | [**string[]**](../Model/string.md)| Sorting criteria in the format: property(,asc|desc). Default sort order is ascending. Multiple sort criteria are supported. | [optional]
 **tag** | **string**| The tag (label). | [optional]
 **unpaged** | **bool**| If true, response is not paginated (but is sorted) | [optional]
 **with_available** | **bool**| with-available | [optional]
 **with_feature** | **bool**| with-feature | [optional]
 **with_workspace** | **bool**| with-workspace | [optional]

### Return type

[**\PersonalizationClient\Model\TagResources**](../Model/TagResources.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/vnd.hw-p13n.tags+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateTag**
> \PersonalizationClient\Model\TagResource updateTag($context, $id, $label)

Update existing tag

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\TagsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 388; // int | id
$label = "label_example"; // string | label

try {
    $result = $apiInstance->updateTag($context, $id, $label);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TagsApi->updateTag: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **id** | **int**| id |
 **label** | **string**| label |

### Return type

[**\PersonalizationClient\Model\TagResource**](../Model/TagResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json, application/hal+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

