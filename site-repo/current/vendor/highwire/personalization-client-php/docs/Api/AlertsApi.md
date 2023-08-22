# PersonalizationClient\AlertsApi

All URIs are relative to *https://104.232.16.4/personalization*

Method | HTTP request | Description
------------- | ------------- | -------------
[**handleDeleteByIdUsingDELETE**](AlertsApi.md#handleDeleteByIdUsingDELETE) | **DELETE** /api/{context}/alerts/{id} | Delete an alert
[**handleGetAvailableUsingGET**](AlertsApi.md#handleGetAvailableUsingGET) | **GET** /api/{context}/alerts/available | Get available alerts
[**handleGetByIdUsingGET**](AlertsApi.md#handleGetByIdUsingGET) | **GET** /api/{context}/alerts/{id} | Get an alert by identifier
[**handleGetListUsingGET**](AlertsApi.md#handleGetListUsingGET) | **GET** /api/{context}/alerts | Get list of alerts
[**handlePatchAnyUsingPATCH**](AlertsApi.md#handlePatchAnyUsingPATCH) | **PATCH** /api/{context}/alerts/{id} | Update an alert
[**handlePostAlertUsingPOST**](AlertsApi.md#handlePostAlertUsingPOST) | **POST** /api/{context}/alerts | Create a new alert


# **handleDeleteByIdUsingDELETE**
> handleDeleteByIdUsingDELETE($context, $id)

Delete an alert

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\AlertsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 388; // int | id

try {
    $apiInstance->handleDeleteByIdUsingDELETE($context, $id);
} catch (Exception $e) {
    echo 'Exception when calling AlertsApi->handleDeleteByIdUsingDELETE: ', $e->getMessage(), PHP_EOL;
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

# **handleGetAvailableUsingGET**
> \PersonalizationClient\Model\AlertResource[] handleGetAvailableUsingGET($context, $atom, $type)

Get available alerts

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\AlertsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$atom = "\"/sgrvv.atom\""; // string | atom
$type = "\"toc\""; // string | type

try {
    $result = $apiInstance->handleGetAvailableUsingGET($context, $atom, $type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AlertsApi->handleGetAvailableUsingGET: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **atom** | **string**| atom | [optional]
 **type** | **string**| type | [optional]

### Return type

[**\PersonalizationClient\Model\AlertResource[]**](../Model/AlertResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handleGetByIdUsingGET**
> \PersonalizationClient\Model\AlertResource handleGetByIdUsingGET($context, $id)

Get an alert by identifier

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\AlertsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 388; // int | id

try {
    $result = $apiInstance->handleGetByIdUsingGET($context, $id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AlertsApi->handleGetByIdUsingGET: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **id** | **int**| id |

### Return type

[**\PersonalizationClient\Model\AlertResource**](../Model/AlertResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/vnd.hw-p13n.alert+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handleGetListUsingGET**
> \PersonalizationClient\Model\AlertResources handleGetListUsingGET($context, $atom, $email, $page, $size, $sort, $type, $with_available, $with_feature, $with_search, $with_workspace)

Get list of alerts

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\AlertsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$atom = "\"/sgrvv.atom\""; // string | atom
$email = "\"pookie@gmail.com\""; // string | Authenticated user's email address as provided by the calling application, eg, <em>pookie@gmail.com</em>.
$page = 1; // int | 0-based page index
$size = 20; // int | Number of alerts to include per page
$sort = array("sort_example"); // string[] | Sort field in ascending (default) or descending order. To sort by a field in descending order, add |desc to the sort-property name, eg, created|desc
$type = "\"toc\""; // string | type
$with_available = "yes"; // bool | with-available
$with_feature = "yes"; // bool | with-feature
$with_search = "yes"; // bool | with-search
$with_workspace = "yes"; // bool | with-workspace

try {
    $result = $apiInstance->handleGetListUsingGET($context, $atom, $email, $page, $size, $sort, $type, $with_available, $with_feature, $with_search, $with_workspace);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AlertsApi->handleGetListUsingGET: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **atom** | **string**| atom | [optional]
 **email** | **string**| Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;pookie@gmail.com&lt;/em&gt;. | [optional]
 **page** | **int**| 0-based page index | [optional]
 **size** | **int**| Number of alerts to include per page | [optional] [default to 20]
 **sort** | [**string[]**](../Model/string.md)| Sort field in ascending (default) or descending order. To sort by a field in descending order, add |desc to the sort-property name, eg, created|desc | [optional]
 **type** | **string**| type | [optional]
 **with_available** | **bool**| with-available | [optional]
 **with_feature** | **bool**| with-feature | [optional]
 **with_search** | **bool**| with-search | [optional]
 **with_workspace** | **bool**| with-workspace | [optional]

### Return type

[**\PersonalizationClient\Model\AlertResources**](../Model/AlertResources.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handlePatchAnyUsingPATCH**
> \PersonalizationClient\Model\AlertResource handlePatchAnyUsingPATCH($context, $id, $days, $delivery, $frequency, $name, $status)

Update an alert

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\AlertsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 388; // int | id
$days = "\"7\""; // string | days
$delivery = "\"email\""; // string | delivery
$frequency = "\"daily\""; // string | frequency
$name = "\"Search alert for my little pony\""; // string | name
$status = "active"; // string | status

try {
    $result = $apiInstance->handlePatchAnyUsingPATCH($context, $id, $days, $delivery, $frequency, $name, $status);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AlertsApi->handlePatchAnyUsingPATCH: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **id** | **int**| id |
 **days** | **string**| days | [optional]
 **delivery** | **string**| delivery | [optional]
 **frequency** | **string**| frequency | [optional]
 **name** | **string**| name | [optional]
 **status** | **string**| status | [optional] [default to active]

### Return type

[**\PersonalizationClient\Model\AlertResource**](../Model/AlertResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json, application/vnd.hw-p13n.alert+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handlePostAlertUsingPOST**
> \PersonalizationClient\Model\AlertResource handlePostAlertUsingPOST($atom, $context, $email, $type, $delivery, $frequency, $name, $status)

Create a new alert

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\AlertsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$atom = "\"/sgrvv.atom\""; // string | atom
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$email = "\"pookie@gmail.com\""; // string | Authenticated user's email address as provided by the calling application, eg, <em>pookie@gmail.com</em>. If this call results in the creation of a new Workspace, then used as the <strong>name</strong> as well as the (hashed) <strong>handle</strong>.
$type = "\"toc\""; // string | type
$delivery = "\"email\""; // string | delivery
$frequency = "\"daily\""; // string | frequency
$name = "\"Search alert for my little pony\""; // string | name
$status = "active"; // string | status

try {
    $result = $apiInstance->handlePostAlertUsingPOST($atom, $context, $email, $type, $delivery, $frequency, $name, $status);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AlertsApi->handlePostAlertUsingPOST: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **atom** | **string**| atom |
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **email** | **string**| Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;pookie@gmail.com&lt;/em&gt;. If this call results in the creation of a new Workspace, then used as the &lt;strong&gt;name&lt;/strong&gt; as well as the (hashed) &lt;strong&gt;handle&lt;/strong&gt;. |
 **type** | **string**| type |
 **delivery** | **string**| delivery | [optional]
 **frequency** | **string**| frequency | [optional]
 **name** | **string**| name | [optional]
 **status** | **string**| status | [optional] [default to active]

### Return type

[**\PersonalizationClient\Model\AlertResource**](../Model/AlertResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json, application/vnd.hw-p13n.alert+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

