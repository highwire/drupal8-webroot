# PersonalizationClient\ExecutionsApi

All URIs are relative to *https://104.232.16.4/personalization*

Method | HTTP request | Description
------------- | ------------- | -------------
[**handlePatchUsingPATCH**](ExecutionsApi.md#handlePatchUsingPATCH) | **PATCH** /api/executions/{id} | handlePatch
[**handlePostUsingPOST**](ExecutionsApi.md#handlePostUsingPOST) | **POST** /api/executions | handlePost


# **handlePatchUsingPATCH**
> \PersonalizationClient\Model\ExecutionResource handlePatchUsingPATCH($id, $status, $alert)

handlePatch

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\ExecutionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 789; // int | id
$status = "status_example"; // string | status
$alert = array(56); // int[] | alert

try {
    $result = $apiInstance->handlePatchUsingPATCH($id, $status, $alert);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ExecutionsApi->handlePatchUsingPATCH: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **int**| id |
 **status** | **string**| status |
 **alert** | [**int[]**](../Model/int.md)| alert | [optional]

### Return type

[**\PersonalizationClient\Model\ExecutionResource**](../Model/ExecutionResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json, application/vnd.hw-alert.exec+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handlePostUsingPOST**
> \PersonalizationClient\Model\ExecutionResource handlePostUsingPOST($execution_data)

handlePost

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\ExecutionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$execution_data = new \PersonalizationClient\Model\ExecutionData(); // \PersonalizationClient\Model\ExecutionData | executionData

try {
    $result = $apiInstance->handlePostUsingPOST($execution_data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ExecutionsApi->handlePostUsingPOST: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **execution_data** | [**\PersonalizationClient\Model\ExecutionData**](../Model/ExecutionData.md)| executionData |

### Return type

[**\PersonalizationClient\Model\ExecutionResource**](../Model/ExecutionResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json, application/vnd.hw-alert.exec-data+json
 - **Accept**: application/json, application/vnd.hw-alert.exec+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

