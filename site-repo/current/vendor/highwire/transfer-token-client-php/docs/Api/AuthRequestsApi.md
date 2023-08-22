# TransferTokenClient\AuthRequestsApi

All URIs are relative to *https://fr-docker-host-dev-01.highwire.org:9015*

Method | HTTP request | Description
------------- | ------------- | -------------
[**getAuth**](AuthRequestsApi.md#getAuth) | **GET** /api/transfer/auth | Get Auth


# **getAuth**
> \TransferTokenClient\Model\Auth getAuth($for_host, $for_ip, $token, $via_host, $target)

Get Auth

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new TransferTokenClient\Api\AuthRequestsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$for_host = "for_host_example"; // string | is the value of the Referer header on the request, if any, eg, the IHS Global portal
$for_ip = "for_ip_example"; // string | is the IP address of the user, asserted by the requester, is required
$token = "token_example"; // string | Signed Base64-encoded JWT
$via_host = "via_host_example"; // string | is the value of the Host header on the request, if any, eg, www.accessengineeringlibrary.com
$target = "target_example"; // string | target uri

try {
    $result = $apiInstance->getAuth($for_host, $for_ip, $token, $via_host, $target);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AuthRequestsApi->getAuth: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **for_host** | **string**| is the value of the Referer header on the request, if any, eg, the IHS Global portal |
 **for_ip** | **string**| is the IP address of the user, asserted by the requester, is required |
 **token** | **string**| Signed Base64-encoded JWT |
 **via_host** | **string**| is the value of the Host header on the request, if any, eg, www.accessengineeringlibrary.com |
 **target** | **string**| target uri | [optional]

### Return type

[**\TransferTokenClient\Model\Auth**](../Model/Auth.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

