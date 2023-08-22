# TransferTokenClient\TokenRequestsApi

All URIs are relative to *https://fr-docker-host-dev-01.highwire.org:9015*

Method | HTTP request | Description
------------- | ------------- | -------------
[**getTransferToken**](TokenRequestsApi.md#getTransferToken) | **GET** /api/transfer/token | Get Transfer Token


# **getTransferToken**
> \TransferTokenClient\Model\Token getTransferToken($api_key, $for_ip, $from_host, $from_ip, $scope, $target, $via_host, $check_ip, $redirect, $ttl, $user_email, $user_name)

Get Transfer Token

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new TransferTokenClient\Api\TokenRequestsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$api_key = "api_key_example"; // string | ${service.api.transfer.token.param.api-key}
$for_ip = "for_ip_example"; // string | ${service.api.transfer.token.param.for-ip}
$from_host = "from_host_example"; // string | ${service.api.transfer.token.param.from-host}
$from_ip = "from_ip_example"; // string | ${service.api.transfer.token.param.from-ip}
$scope = "scope_example"; // string | ${service.api.transfer.token.param.scope}
$target = "target_example"; // string | ${service.api.transfer.token.param.target}
$via_host = "via_host_example"; // string | ${service.api.transfer.token.param.via-host}
$check_ip = true; // bool | ${service.api.transfer.token.param.check-ip}
$redirect = "redirect_example"; // string | ${service.api.transfer.token.param.redirect}
$ttl = 5; // int | ${service.api.transfer.token.param.ttl}
$user_email = "user_email_example"; // string | ${service.api.transfer.token.param.user-email}
$user_name = "user_name_example"; // string | ${service.api.transfer.token.param.user-name}

try {
    $result = $apiInstance->getTransferToken($api_key, $for_ip, $from_host, $from_ip, $scope, $target, $via_host, $check_ip, $redirect, $ttl, $user_email, $user_name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TokenRequestsApi->getTransferToken: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **api_key** | **string**| ${service.api.transfer.token.param.api-key} |
 **for_ip** | **string**| ${service.api.transfer.token.param.for-ip} |
 **from_host** | **string**| ${service.api.transfer.token.param.from-host} |
 **from_ip** | **string**| ${service.api.transfer.token.param.from-ip} |
 **scope** | **string**| ${service.api.transfer.token.param.scope} |
 **target** | **string**| ${service.api.transfer.token.param.target} |
 **via_host** | **string**| ${service.api.transfer.token.param.via-host} |
 **check_ip** | **bool**| ${service.api.transfer.token.param.check-ip} | [optional] [default to true]
 **redirect** | **string**| ${service.api.transfer.token.param.redirect} | [optional]
 **ttl** | **int**| ${service.api.transfer.token.param.ttl} | [optional] [default to 5]
 **user_email** | **string**| ${service.api.transfer.token.param.user-email} | [optional]
 **user_name** | **string**| ${service.api.transfer.token.param.user-name} | [optional]

### Return type

[**\TransferTokenClient\Model\Token**](../Model/Token.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

