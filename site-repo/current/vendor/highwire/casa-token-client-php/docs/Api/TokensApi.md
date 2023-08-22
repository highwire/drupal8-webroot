# CasaTokenClient\TokensApi

All URIs are relative to *https://fr-docker-host-dev-01.highwire.org:9004*

Method | HTTP request | Description
------------- | ------------- | -------------
[**getCasaToken**](TokensApi.md#getCasaToken) | **GET** /api/casa/tokens/{context} | Create a CASA token to be used as a casa_token


# **getCasaToken**
> string getCasaToken($context, $subnet, $subscriber, $ttl, $now)

Create a CASA token to be used as a casa_token

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new CasaTokenClient\Api\TokensApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$subnet = "\"98.234.179.0/20\""; // string | In order to protect users' privacy, Google does not send the specific address the user was seen on campus but instead they send an address range. The range could be either IPv4 or IPv6 although as a practical matter, we'll only see v4 until we include v6 addresses in the Subscriber Links data we send to Google.
$subscriber = "\"http://www.springerpub.com/google/casa/TestOrg01\""; // string | Subscriber ID, usually an organization.
$ttl = "\"1 minute\""; // string | Used to establish token's expiration time.
$now = new \DateTime("\"2019-09-25T17:31:00Z\""); // \DateTime | A date-time to use as the current time in place of actual time.

try {
    $result = $apiInstance->getCasaToken($context, $subnet, $subscriber, $ttl, $now);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TokensApi->getCasaToken: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **subnet** | **string**| In order to protect users&#39; privacy, Google does not send the specific address the user was seen on campus but instead they send an address range. The range could be either IPv4 or IPv6 although as a practical matter, we&#39;ll only see v4 until we include v6 addresses in the Subscriber Links data we send to Google. |
 **subscriber** | **string**| Subscriber ID, usually an organization. |
 **ttl** | **string**| Used to establish token&#39;s expiration time. |
 **now** | **\DateTime**| A date-time to use as the current time in place of actual time. | [optional]

### Return type

**string**

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: */*

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

