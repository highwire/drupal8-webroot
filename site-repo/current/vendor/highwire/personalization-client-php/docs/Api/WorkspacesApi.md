# PersonalizationClient\WorkspacesApi

All URIs are relative to *https://104.232.16.4/personalization*

Method | HTTP request | Description
------------- | ------------- | -------------
[**handleCreateWorkspaceUsingParamsUsingPOST**](WorkspacesApi.md#handleCreateWorkspaceUsingParamsUsingPOST) | **POST** /api/{context}/workspaces | Create a new workspace
[**handleDeleteWorkspaceUsingDELETE**](WorkspacesApi.md#handleDeleteWorkspaceUsingDELETE) | **DELETE** /api/{context}/workspaces/{handle} | Delete an existing workspace by its handle
[**handleGetWorkspaceByEmailUsingGET**](WorkspacesApi.md#handleGetWorkspaceByEmailUsingGET) | **GET** /api/{context}/workspaces | Retrieve an existing workspace by its email
[**handleGetWorkspaceByHandleUsingGET**](WorkspacesApi.md#handleGetWorkspaceByHandleUsingGET) | **GET** /api/{context}/workspaces/{handle} | Retrieve an existing workspace by its handle


# **handleCreateWorkspaceUsingParamsUsingPOST**
> \PersonalizationClient\Model\WorkspaceResource handleCreateWorkspaceUsingParamsUsingPOST($context, $email, $handle, $name)

Create a new workspace

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\WorkspacesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$email = "\"pookie@gmail.com\""; // string | Authenticated user's email address as provided by the calling application, eg, <em>pookie@gmail.com</em>. Used as the default <strong>name</strong> if no name is supplied, and also as the basis of generating a <strong>handle</strong> if no handle is supplied.
$handle = "\"e40b7df3\""; // string | Handle for the workspace as provided by the calling application, eg, <em>e40b7df3</em>. If not supplied, one will be generated using the (required) <strong>email</strong> address
$name = "\"Craig \\\"Pookie\\\" Jurney\""; // string | A name for the workspace as provided by the calling application, eg, <em>Craig \"Pookie\" Jurney</em>. If none is supplied the <strong>email</strong> is used as the value.

try {
    $result = $apiInstance->handleCreateWorkspaceUsingParamsUsingPOST($context, $email, $handle, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WorkspacesApi->handleCreateWorkspaceUsingParamsUsingPOST: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **email** | **string**| Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;pookie@gmail.com&lt;/em&gt;. Used as the default &lt;strong&gt;name&lt;/strong&gt; if no name is supplied, and also as the basis of generating a &lt;strong&gt;handle&lt;/strong&gt; if no handle is supplied. |
 **handle** | **string**| Handle for the workspace as provided by the calling application, eg, &lt;em&gt;e40b7df3&lt;/em&gt;. If not supplied, one will be generated using the (required) &lt;strong&gt;email&lt;/strong&gt; address | [optional]
 **name** | **string**| A name for the workspace as provided by the calling application, eg, &lt;em&gt;Craig \&quot;Pookie\&quot; Jurney&lt;/em&gt;. If none is supplied the &lt;strong&gt;email&lt;/strong&gt; is used as the value. | [optional]

### Return type

[**\PersonalizationClient\Model\WorkspaceResource**](../Model/WorkspaceResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json, application/vnd.hw-p13n.workspace+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handleDeleteWorkspaceUsingDELETE**
> handleDeleteWorkspaceUsingDELETE($context, $handle)

Delete an existing workspace by its handle

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\WorkspacesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$handle = "\"e40b7df3\""; // string | Handle for the workspace as provided by the calling application, eg, <em>e40b7df3</em>.

try {
    $apiInstance->handleDeleteWorkspaceUsingDELETE($context, $handle);
} catch (Exception $e) {
    echo 'Exception when calling WorkspacesApi->handleDeleteWorkspaceUsingDELETE: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **handle** | **string**| Handle for the workspace as provided by the calling application, eg, &lt;em&gt;e40b7df3&lt;/em&gt;. |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: */*

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handleGetWorkspaceByEmailUsingGET**
> \PersonalizationClient\Model\WorkspaceResource handleGetWorkspaceByEmailUsingGET($context, $email)

Retrieve an existing workspace by its email

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\WorkspacesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$email = "\"pookie@gmail.com\""; // string | Authenticated user's email address as provided by the calling application, eg, <em>pookie@gmail.com</em>.

try {
    $result = $apiInstance->handleGetWorkspaceByEmailUsingGET($context, $email);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WorkspacesApi->handleGetWorkspaceByEmailUsingGET: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **email** | **string**| Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;pookie@gmail.com&lt;/em&gt;. |

### Return type

[**\PersonalizationClient\Model\WorkspaceResource**](../Model/WorkspaceResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json, application/vnd.hw-p13n.workspace+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **handleGetWorkspaceByHandleUsingGET**
> \PersonalizationClient\Model\WorkspaceResource handleGetWorkspaceByHandleUsingGET($context, $handle)

Retrieve an existing workspace by its handle

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\WorkspacesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$handle = "\"e40b7df3\""; // string | Handle for the workspace as provided by the calling application, eg, <em>e40b7df3</em>.

try {
    $result = $apiInstance->handleGetWorkspaceByHandleUsingGET($context, $handle);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling WorkspacesApi->handleGetWorkspaceByHandleUsingGET: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **handle** | **string**| Handle for the workspace as provided by the calling application, eg, &lt;em&gt;e40b7df3&lt;/em&gt;. |

### Return type

[**\PersonalizationClient\Model\WorkspaceResource**](../Model/WorkspaceResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json, application/vnd.hw-p13n.workspace+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

