# PersonalizationClient\PreferencesApi

All URIs are relative to *https://104.232.16.4/personalization*

Method | HTTP request | Description
------------- | ------------- | -------------
[**deletePreference**](PreferencesApi.md#deletePreference) | **DELETE** /api/{context}/preferences/{id} | Delete existing preference-setting
[**getPreferenceSetting1**](PreferencesApi.md#getPreferenceSetting1) | **GET** /api/{context}/preferences/{id} | Retrieve existing preference-setting
[**getPreferences**](PreferencesApi.md#getPreferences) | **GET** /api/{context}/preferences | Get preferences for user
[**postPreferences**](PreferencesApi.md#postPreferences) | **POST** /api/{context}/preferences | Create (or update) preference-settings
[**updatePreferenceSetting**](PreferencesApi.md#updatePreferenceSetting) | **PATCH** /api/{context}/preference-settings/{id} | Update an existing preference-setting


# **deletePreference**
> deletePreference($context, $id)

Delete existing preference-setting

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\PreferencesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 388; // int | The identifier (key) of the resource as stored in its repository, usually an integer assigned sequentially (from '1') by the database engine. There is no semantic attached to  an id, it is just a number.

try {
    $apiInstance->deletePreference($context, $id);
} catch (Exception $e) {
    echo 'Exception when calling PreferencesApi->deletePreference: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **id** | **int**| The identifier (key) of the resource as stored in its repository, usually an integer assigned sequentially (from &#39;1&#39;) by the database engine. There is no semantic attached to  an id, it is just a number. |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json, application/hal+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPreferenceSetting1**
> \PersonalizationClient\Model\PreferenceSettingResource getPreferenceSetting1($context, $id, $with_workspace)

Retrieve existing preference-setting

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\PreferencesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 388; // int | The identifier (key) of the resource as stored in its repository, usually an integer assigned sequentially (from '1') by the database engine. There is no semantic attached to  an id, it is just a number.
$with_workspace = true; // bool | For any resource in the response, optionally include an <strong>owner</strong> property containing the workspace properties like <em>email</em>, <em>handle</em>, and <em>name</em>.

try {
    $result = $apiInstance->getPreferenceSetting1($context, $id, $with_workspace);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PreferencesApi->getPreferenceSetting1: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **id** | **int**| The identifier (key) of the resource as stored in its repository, usually an integer assigned sequentially (from &#39;1&#39;) by the database engine. There is no semantic attached to  an id, it is just a number. |
 **with_workspace** | **bool**| For any resource in the response, optionally include an &lt;strong&gt;owner&lt;/strong&gt; property containing the workspace properties like &lt;em&gt;email&lt;/em&gt;, &lt;em&gt;handle&lt;/em&gt;, and &lt;em&gt;name&lt;/em&gt;. | [optional]

### Return type

[**\PersonalizationClient\Model\PreferenceSettingResource**](../Model/PreferenceSettingResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json, application/hal+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPreferences**
> \PersonalizationClient\Model\PreferencesResource getPreferences($context, $email, $profile, $setting, $with_workspace)

Get preferences for user

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\PreferencesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$email = "\"pookie@gmail.com\""; // string | Authenticated user's email address as provided by the calling application, eg, <em>pookie@gmail.com</em>.
$profile = "\"privacy\""; // string | profile
$setting = "\"consents-to-marketing\""; // string | setting
$with_workspace = "yes"; // bool | withWorkspace

try {
    $result = $apiInstance->getPreferences($context, $email, $profile, $setting, $with_workspace);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PreferencesApi->getPreferences: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **email** | **string**| Authenticated user&#39;s email address as provided by the calling application, eg, &lt;em&gt;pookie@gmail.com&lt;/em&gt;. | [optional]
 **profile** | **string**| profile | [optional]
 **setting** | **string**| setting | [optional]
 **with_workspace** | **bool**| withWorkspace | [optional]

### Return type

[**\PersonalizationClient\Model\PreferencesResource**](../Model/PreferencesResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **postPreferences**
> \PersonalizationClient\Model\PreferencesResource postPreferences($context, $preferences_data)

Create (or update) preference-settings

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\PreferencesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$preferences_data = new \PersonalizationClient\Model\PreferencesData(); // \PersonalizationClient\Model\PreferencesData | preferencesData

try {
    $result = $apiInstance->postPreferences($context, $preferences_data);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PreferencesApi->postPreferences: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **preferences_data** | [**\PersonalizationClient\Model\PreferencesData**](../Model/PreferencesData.md)| preferencesData |

### Return type

[**\PersonalizationClient\Model\PreferencesResource**](../Model/PreferencesResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/vnd.hw-p13n.pref-data+json, application/json
 - **Accept**: application/json, application/hal+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updatePreferenceSetting**
> \PersonalizationClient\Model\PreferenceSettingResource updatePreferenceSetting($context, $id, $patch)

Update an existing preference-setting

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\PreferencesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 388; // int | The identifier (key) of the resource as stored in its repository, usually an integer assigned sequentially (from '1') by the database engine. There is no semantic attached to  an id, it is just a number.
$patch = new \PersonalizationClient\Model\PreferenceSettingPatch(); // \PersonalizationClient\Model\PreferenceSettingPatch | patch

try {
    $result = $apiInstance->updatePreferenceSetting($context, $id, $patch);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PreferencesApi->updatePreferenceSetting: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **context** | **string**| The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. |
 **id** | **int**| The identifier (key) of the resource as stored in its repository, usually an integer assigned sequentially (from &#39;1&#39;) by the database engine. There is no semantic attached to  an id, it is just a number. |
 **patch** | [**\PersonalizationClient\Model\PreferenceSettingPatch**](../Model/PreferenceSettingPatch.md)| patch |

### Return type

[**\PersonalizationClient\Model\PreferenceSettingResource**](../Model/PreferenceSettingResource.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json, application/hal+json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

