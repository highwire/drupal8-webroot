# PersonalizationClient\CapturesApi

All URIs are relative to *https://104.232.16.4/personalization*

Method | HTTP request | Description
------------- | ------------- | -------------
[**capturesForSigma**](CapturesApi.md#capturesForSigma) | **POST** /api/{context}/captures/sigma | Capture data from supplied Simga OIDC data
[**capturesLastActive**](CapturesApi.md#capturesLastActive) | **POST** /api/{context}/captures/active | Capture Last Active data
[**getCaptures**](CapturesApi.md#getCaptures) | **GET** /api/{context}/captures | Get all captures for a user


# **capturesForSigma**
> capturesForSigma($context, $source_data, $user)

Capture data from supplied Simga OIDC data

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\CapturesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$source_data = "source_data_example"; // string | sourceData
$user = "\"cjurney@gmail.com\""; // string | ${p13n.api.params.user}

try {
    $apiInstance->capturesForSigma($context, $source_data, $user);
} catch (Exception $e) {
    echo 'Exception when calling CapturesApi->capturesForSigma: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **source_data** | **string**| sourceData |
 **user** | **string**| ${p13n.api.params.user} |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/vnd.hw.sigma+json
 - **Accept**: application/json, application/hal+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **capturesLastActive**
> capturesLastActive($context, $source_data, $user)

Capture Last Active data

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\CapturesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$source_data = "source_data_example"; // string | sourceData
$user = "\"cjurney@gmail.com\""; // string | ${p13n.api.params.user}

try {
    $apiInstance->capturesLastActive($context, $source_data, $user);
} catch (Exception $e) {
    echo 'Exception when calling CapturesApi->capturesLastActive: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **source_data** | **string**| sourceData |
 **user** | **string**| ${p13n.api.params.user} |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/vnd.hw.sigma+json
 - **Accept**: application/json, application/hal+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getCaptures**
> \PersonalizationClient\Model\CaptureGroup[] getCaptures($context, $user, $group)

Get all captures for a user

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\CapturesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$user = "\"cjurney@gmail.com\""; // string | ${p13n.api.params.user}
$group = "\"sigma\""; // string | group

try {
    $result = $apiInstance->getCaptures($context, $user, $group);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CapturesApi->getCaptures: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **user** | **string**| ${p13n.api.params.user} |
 **group** | **string**| group | [optional]

### Return type

[**\PersonalizationClient\Model\CaptureGroup[]**](../Model/CaptureGroup.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/vnd.hw-p13n.capture+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

