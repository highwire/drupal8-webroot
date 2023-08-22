# MarcRecordsClient\RecordsApi

All URIs are relative to *https://fr-docker-host-dev-01.highwire.org:9003*

Method | HTTP request | Description
------------- | ------------- | -------------
[**getAllRecords**](RecordsApi.md#getAllRecords) | **GET** /data/{context}/records | Get all MARC records in the context.


# **getAllRecords**
> string getAllRecords($context, $content_type, $form, $from, $until)

Get all MARC records in the context.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new MarcRecordsClient\Api\RecordsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"mheaeworks\""; // string | Application context
$content_type = array("content_type_example"); // string[] | Content type
$form = "form_example"; // string | form
$from = "\"2018-01-01\""; // string | From date
$until = "\"2019-01-01\""; // string | Until date

try {
    $result = $apiInstance->getAllRecords($context, $content_type, $form, $from, $until);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecordsApi->getAllRecords: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| Application context |
 **content_type** | [**string[]**](../Model/string.md)| Content type | [optional]
 **form** | **string**| form | [optional]
 **from** | **string**| From date | [optional]
 **until** | **string**| Until date | [optional]

### Return type

**string**

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/marc, application/marcxml+xml

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

