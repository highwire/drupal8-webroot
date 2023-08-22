# PersonalizationClient\SearchesApi

All URIs are relative to *https://104.232.16.4/personalization*

Method | HTTP request | Description
------------- | ------------- | -------------
[**deleteSearch**](SearchesApi.md#deleteSearch) | **DELETE** /api/{context}/searches/{id} | Delete a saved-search
[**handleGetSearchUsingGET**](SearchesApi.md#handleGetSearchUsingGET) | **GET** /api/{context}/searches/{id} | Get a saved-search
[**handleGetSearchesUsingGET**](SearchesApi.md#handleGetSearchesUsingGET) | **GET** /api/{context}/searches | Get all saved-searches for the user
[**handlePatchParamsUsingPATCH**](SearchesApi.md#handlePatchParamsUsingPATCH) | **PATCH** /api/{context}/searches/{id} | Update a saved-search
[**handlePostUsingBodyUsingPOST**](SearchesApi.md#handlePostUsingBodyUsingPOST) | **POST** /api/{context}/searches | Create a new saved-search


# **deleteSearch**
> deleteSearch($context, $id)

Delete a saved-search

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\SearchesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | context
$id = 388; // int | id

try {
    $apiInstance->deleteSearch($context, $id);
} catch (Exception $e) {
    echo 'Exception when calling SearchesApi->deleteSearch: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| context |
 **id** | **int**| id |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: */*

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handleGetSearchUsingGET**
> \PersonalizationClient\Model\SearchResource handleGetSearchUsingGET($context, $id, $with_alert, $with_feature, $with_workspace)

Get a saved-search

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\SearchesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 388; // int | id
$with_alert = "yes"; // bool | with-alert
$with_feature = "yes"; // bool | with-feature
$with_workspace = "yes"; // bool | with-workspace

try {
    $result = $apiInstance->handleGetSearchUsingGET($context, $id, $with_alert, $with_feature, $with_workspace);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SearchesApi->handleGetSearchUsingGET: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **id** | **int**| id |
 **with_alert** | **bool**| with-alert | [optional]
 **with_feature** | **bool**| with-feature | [optional]
 **with_workspace** | **bool**| with-workspace | [optional]

### Return type

[**\PersonalizationClient\Model\SearchResource**](../Model/SearchResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/vnd.hw-p13n.search+json, application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handleGetSearchesUsingGET**
> \PersonalizationClient\Model\SearchResources handleGetSearchesUsingGET($context, $email, $page, $size, $sort, $with_alert, $with_available, $with_feature, $with_workspace)

Get all saved-searches for the user

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\SearchesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$email = "\"pookie@gmail.com\""; // string | Authenticated user's email address as provided by the calling application, eg, <em>pookie@gmail.com</em>.
$page = 1; // int | 0-based page index
$size = 20; // int | Number of alerts to include per page
$sort = array("sort_example"); // string[] | Sort field in ascending (default) or descending order. To sort by a field in descending order, add |desc to the sort-property name, eg, created|desc
$with_alert = "yes"; // bool | with-alert
$with_available = "yes"; // bool | with-available
$with_feature = "yes"; // bool | with-feature
$with_workspace = "yes"; // bool | with-workspace

try {
    $result = $apiInstance->handleGetSearchesUsingGET($context, $email, $page, $size, $sort, $with_alert, $with_available, $with_feature, $with_workspace);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SearchesApi->handleGetSearchesUsingGET: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **email** | **string**| Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;pookie@gmail.com&lt;/em&gt;. |
 **page** | **int**| 0-based page index | [optional]
 **size** | **int**| Number of alerts to include per page | [optional] [default to 20]
 **sort** | [**string[]**](../Model/string.md)| Sort field in ascending (default) or descending order. To sort by a field in descending order, add |desc to the sort-property name, eg, created|desc | [optional]
 **with_alert** | **bool**| with-alert | [optional]
 **with_available** | **bool**| with-available | [optional]
 **with_feature** | **bool**| with-feature | [optional]
 **with_workspace** | **bool**| with-workspace | [optional]

### Return type

[**\PersonalizationClient\Model\SearchResources**](../Model/SearchResources.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handlePatchParamsUsingPATCH**
> \PersonalizationClient\Model\SearchResource handlePatchParamsUsingPATCH($context, $id, $alert_frequency, $alert_status, $label, $notes)

Update a saved-search

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\SearchesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | context
$id = 388; // int | id
$alert_frequency = "\"daily\""; // string | alert-frequency
$alert_status = "\"active\""; // string | alert-status
$label = "\"My little search\""; // string | label
$notes = "notes_example"; // string | notes

try {
    $result = $apiInstance->handlePatchParamsUsingPATCH($context, $id, $alert_frequency, $alert_status, $label, $notes);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SearchesApi->handlePatchParamsUsingPATCH: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| context |
 **id** | **int**| id |
 **alert_frequency** | **string**| alert-frequency | [optional]
 **alert_status** | **string**| alert-status | [optional]
 **label** | **string**| label | [optional]
 **notes** | **string**| notes | [optional]

### Return type

[**\PersonalizationClient\Model\SearchResource**](../Model/SearchResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/vnd.hw-p13n.search+json, application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handlePostUsingBodyUsingPOST**
> \PersonalizationClient\Model\SearchResource handlePostUsingBodyUsingPOST($context, $data, $email, $alert_frequency)

Create a new saved-search

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\SearchesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$data = new \PersonalizationClient\Model\SearchData(); // \PersonalizationClient\Model\SearchData | Data used to create a new saved-search
$email = "\"pookie@gmail.com\""; // string | Authenticated user's email address as provided by the calling application, eg, <em>pookie@gmail.com</em>. If this call results in the creation of a new Workspace, then used as the <strong>name</strong> as well as the (hashed) <strong>handle</strong>.
$alert_frequency = "\"daily\""; // string | alert-frequency

try {
    $result = $apiInstance->handlePostUsingBodyUsingPOST($context, $data, $email, $alert_frequency);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SearchesApi->handlePostUsingBodyUsingPOST: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **data** | [**\PersonalizationClient\Model\SearchData**](../Model/SearchData.md)| Data used to create a new saved-search |
 **email** | **string**| Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;pookie@gmail.com&lt;/em&gt;. If this call results in the creation of a new Workspace, then used as the &lt;strong&gt;name&lt;/strong&gt; as well as the (hashed) &lt;strong&gt;handle&lt;/strong&gt;. |
 **alert_frequency** | **string**| alert-frequency | [optional]

### Return type

[**\PersonalizationClient\Model\SearchResource**](../Model/SearchResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/vnd.hw-p13n.search-data+json, application/json
 - **Accept**: application/vnd.hw-p13n.search+json, application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

