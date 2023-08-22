# PersonalizationClient\MarkersApi

All URIs are relative to *https://104.232.16.4/personalization*

Method | HTTP request | Description
------------- | ------------- | -------------
[**handleDeleteMarkerUsingDELETE**](MarkersApi.md#handleDeleteMarkerUsingDELETE) | **DELETE** /api/{context}/markers/{id} | Delete a marker
[**handleGetMarkerUsingGET**](MarkersApi.md#handleGetMarkerUsingGET) | **GET** /api/{context}/markers/{id} | Get a marker by its ID
[**handleGetMarkersUsingGET**](MarkersApi.md#handleGetMarkersUsingGET) | **GET** /api/{context}/markers | Get markers
[**handlePostContentMarkerUsingPOST**](MarkersApi.md#handlePostContentMarkerUsingPOST) | **POST** /api/{context}/markers | handlePostContentMarker


# **handleDeleteMarkerUsingDELETE**
> handleDeleteMarkerUsingDELETE($context, $id)

Delete a marker

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\MarkersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 388; // int | id

try {
    $apiInstance->handleDeleteMarkerUsingDELETE($context, $id);
} catch (Exception $e) {
    echo 'Exception when calling MarkersApi->handleDeleteMarkerUsingDELETE: ', $e->getMessage(), PHP_EOL;
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
 - **Accept**: */*

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handleGetMarkerUsingGET**
> \PersonalizationClient\Model\MarkerResource handleGetMarkerUsingGET($context, $id, $with_feature, $with_workspace)

Get a marker by its ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\MarkersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 388; // int | id
$with_feature = "yes"; // bool | withFeature
$with_workspace = "yes"; // bool | withWorkspace

try {
    $result = $apiInstance->handleGetMarkerUsingGET($context, $id, $with_feature, $with_workspace);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling MarkersApi->handleGetMarkerUsingGET: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **id** | **int**| id |
 **with_feature** | **bool**| withFeature | [optional]
 **with_workspace** | **bool**| withWorkspace | [optional]

### Return type

[**\PersonalizationClient\Model\MarkerResource**](../Model/MarkerResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json, application/vnd.hw-p13n.marker+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handleGetMarkersUsingGET**
> \PersonalizationClient\Model\MarkerResources handleGetMarkersUsingGET($context, $email, $atom, $content_type, $include, $location, $page, $size, $sort, $with_available, $with_workspace, $with_feature)

Get markers

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\MarkersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$email = "\"pookie@gmail.com\""; // string | Authenticated user's email address as provided by the calling application.
$atom = "\"/sgrwfccn/12/2/48.atom\""; // string | The resource that is marked, eg, an Atom.
$content_type = "\"article\""; // string | The type of Atom that is marked, eg, article, chapter, figure or the like.
$include = array("include_example"); // string[] | Type of markers to include, eg, <em>content</em>, may occur multiple times.
$location = "\"http://connect.springerpub.com/authors\""; // string | The page that is marked, eg, a location in the site.
$page = 1; // int | 0-based page index
$size = 20; // int | Number of alerts to include per page
$sort = array("sort_example"); // string[] | Sort field in ascending (default) or descending order. To sort by a field in descending order, add |desc to the sort-property name, eg, created|desc
$with_available = "yes"; // bool | with-available
$with_workspace = "yes"; // bool | with-workspace
$with_feature = "yes"; // bool | with-feature

try {
    $result = $apiInstance->handleGetMarkersUsingGET($context, $email, $atom, $content_type, $include, $location, $page, $size, $sort, $with_available, $with_workspace, $with_feature);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling MarkersApi->handleGetMarkersUsingGET: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **email** | **string**| Authenticated user&#39;s email address as provided by the calling application. |
 **atom** | **string**| The resource that is marked, eg, an Atom. | [optional]
 **content_type** | **string**| The type of Atom that is marked, eg, article, chapter, figure or the like. | [optional]
 **include** | [**string[]**](../Model/string.md)| Type of markers to include, eg, &lt;em&gt;content&lt;/em&gt;, may occur multiple times. | [optional]
 **location** | **string**| The page that is marked, eg, a location in the site. | [optional]
 **page** | **int**| 0-based page index | [optional]
 **size** | **int**| Number of alerts to include per page | [optional] [default to 20]
 **sort** | [**string[]**](../Model/string.md)| Sort field in ascending (default) or descending order. To sort by a field in descending order, add |desc to the sort-property name, eg, created|desc | [optional]
 **with_available** | **bool**| with-available | [optional]
 **with_workspace** | **bool**| with-workspace | [optional]
 **with_feature** | **bool**| with-feature | [optional]

### Return type

[**\PersonalizationClient\Model\MarkerResources**](../Model/MarkerResources.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handlePostContentMarkerUsingPOST**
> \PersonalizationClient\Model\MarkerResource handlePostContentMarkerUsingPOST($context, $marker_data)

handlePostContentMarker

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\MarkersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "context_example"; // string | context
$marker_data = new \PersonalizationClient\Model\MarkerData(); // \PersonalizationClient\Model\MarkerData | markerData

try {
    $result = $apiInstance->handlePostContentMarkerUsingPOST($context, $marker_data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling MarkersApi->handlePostContentMarkerUsingPOST: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| context |
 **marker_data** | [**\PersonalizationClient\Model\MarkerData**](../Model/MarkerData.md)| markerData |

### Return type

[**\PersonalizationClient\Model\MarkerResource**](../Model/MarkerResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/vnd.hw-p13n.marker-data+json, application/json
 - **Accept**: application/json, application/vnd.hw-p13n.marker+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

