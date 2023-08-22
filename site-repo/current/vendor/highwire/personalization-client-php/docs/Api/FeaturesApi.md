# PersonalizationClient\FeaturesApi

All URIs are relative to *https://104.232.16.4/personalization*

Method | HTTP request | Description
------------- | ------------- | -------------
[**getFeatures**](FeaturesApi.md#getFeatures) | **GET** /api/{context}/features | Get all features for a context


# **getFeatures**
> \PersonalizationClient\Model\ServiceFeatures getFeatures($context)

Get all features for a context

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\FeaturesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.

try {
    $result = $apiInstance->getFeatures($context);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FeaturesApi->getFeatures: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |

### Return type

[**\PersonalizationClient\Model\ServiceFeatures**](../Model/ServiceFeatures.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/vnd.hw-p13n.feature+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

