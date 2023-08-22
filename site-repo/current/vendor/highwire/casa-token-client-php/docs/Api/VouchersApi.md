# CasaTokenClient\VouchersApi

All URIs are relative to *https://fr-docker-host-dev-01.highwire.org:9004*

Method | HTTP request | Description
------------- | ------------- | -------------
[**getCasaVoucher**](VouchersApi.md#getCasaVoucher) | **GET** /api/casa/vouchers/{context} | Redeem a CASA token for a voucher)


# **getCasaVoucher**
> \CasaTokenClient\Model\ResponseData getCasaVoucher($client_ip, $context, $for_host, $for_resource, $token, $from_host)

Redeem a CASA token for a voucher)

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new CasaTokenClient\Api\VouchersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$client_ip = "\"50.225.193.59\""; // string | The client IP address of the originating request, asserted by the requester. If Ipv4 then the value may be either dot-decimal or colon-delimited; if Ipv6, it must be colon-delimited.
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$for_host = "\"www.accessengineeringlibrary.com\""; // string | The value of any <code>Host</code> header from the originating request, asserted by the requester. May be empty.
$for_resource = "\"https://connect.springerpub.com/content/sgrrrpe/33/3/184\""; // string | A URL for the target resource from the originating request, asserted by the requester. Depending on the action, it might be used as a destination for a redirect directive in our response or as input into an authorization rule, or both.
$token = "\"CnIDaj4aQi4AAAAA:VLbNdcyXyHWJ15xjQv4V8QNB6kOqDg0jg75gqwQvoKJVR3Fc2PG-M98Z0rOE3iJAwkd3PfbrR8zetQ\""; // string | CASA token (from Google) consisting of two (URL-safe) Base64-encoded segments separated by a colon. The first segment is a random number generated for each token. This makes each token unique and can be used to avoid reuse; the second segment is itself a 3-part (colon-separated) value: <ol><li>Timestamp when the token was generated. The timestamp is a 64-bit integer containingthe number of microseconds since Unix epoch.</li><li>Subscriber-ID for the user’s affiliation. The ID here is the subscriber-ID included by thepublisher in their Subscriber Links file(s).</li><li>The network for the user's campus. The value is a CIDR notated subnet in which the user's actual IP address should be contained.</li></ol>
$from_host = "\"scholar.google.com\""; // string | The value of any <code>Referer</code> [<em><sic/em>] header from the originating request, asserted by the requester. May be empty.

try {
    $result = $apiInstance->getCasaVoucher($client_ip, $context, $for_host, $for_resource, $token, $from_host);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling VouchersApi->getCasaVoucher: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **client_ip** | **string**| The client IP address of the originating request, asserted by the requester. If Ipv4 then the value may be either dot-decimal or colon-delimited; if Ipv6, it must be colon-delimited. |
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **for_host** | **string**| The value of any &lt;code&gt;Host&lt;/code&gt; header from the originating request, asserted by the requester. May be empty. |
 **for_resource** | **string**| A URL for the target resource from the originating request, asserted by the requester. Depending on the action, it might be used as a destination for a redirect directive in our response or as input into an authorization rule, or both. |
 **token** | **string**| CASA token (from Google) consisting of two (URL-safe) Base64-encoded segments separated by a colon. The first segment is a random number generated for each token. This makes each token unique and can be used to avoid reuse; the second segment is itself a 3-part (colon-separated) value: &lt;ol&gt;&lt;li&gt;Timestamp when the token was generated. The timestamp is a 64-bit integer containingthe number of microseconds since Unix epoch.&lt;/li&gt;&lt;li&gt;Subscriber-ID for the user’s affiliation. The ID here is the subscriber-ID included by thepublisher in their Subscriber Links file(s).&lt;/li&gt;&lt;li&gt;The network for the user&#39;s campus. The value is a CIDR notated subnet in which the user&#39;s actual IP address should be contained.&lt;/li&gt;&lt;/ol&gt; |
 **from_host** | **string**| The value of any &lt;code&gt;Referer&lt;/code&gt; [&lt;em&gt;&lt;sic/em&gt;] header from the originating request, asserted by the requester. May be empty. | [optional]

### Return type

[**\CasaTokenClient\Model\ResponseData**](../Model/ResponseData.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: */*

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

