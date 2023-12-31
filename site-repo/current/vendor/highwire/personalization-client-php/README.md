# personalization-client-php
Craig's <strong>best</strong> attempt at generated API documentation for web use.

This PHP package is automatically generated by the [Swagger Codegen](https://github.com/swagger-api/swagger-codegen) project:

- API version: 1.0.0
- Build package: io.swagger.codegen.languages.PhpClientCodegen
For more information, please visit [https://www.linkedin.com/in/craigjurney/](https://www.linkedin.com/in/craigjurney/)

## Requirements

PHP 5.5 and later

## Installation & Usage
### Composer

To install the bindings via [Composer](http://getcomposer.org/), add the following to `composer.json`:

```
{
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/highwire/personalization-client-php.git"
    }
  ],
  "require": {
    "highwire/personalization-client-php": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
    require_once('/path/to/personalization-client-php/vendor/autoload.php');
```

## Tests

To run the unit tests:

```
composer install
./vendor/bin/phpunit
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new PersonalizationClient\Api\AlertsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$context = "\"sgrworks\""; // string | The application context for this service, eg, <em>sgrworks</em>. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the <strong>context</strong> semantic scopes the service data in a way that the calling application can control.
$id = 388; // int | id

try {
    $apiInstance->handleDeleteByIdUsingDELETE($context, $id);
} catch (Exception $e) {
    echo 'Exception when calling AlertsApi->handleDeleteByIdUsingDELETE: ', $e->getMessage(), PHP_EOL;
}

?>
```

## Documentation for API Endpoints

All URIs are relative to *https://104.232.16.4/personalization*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*AlertsApi* | [**handleDeleteByIdUsingDELETE**](docs/Api/AlertsApi.md#handledeletebyidusingdelete) | **DELETE** /api/{context}/alerts/{id} | Delete an alert
*AlertsApi* | [**handleGetAvailableUsingGET**](docs/Api/AlertsApi.md#handlegetavailableusingget) | **GET** /api/{context}/alerts/available | Get available alerts
*AlertsApi* | [**handleGetByIdUsingGET**](docs/Api/AlertsApi.md#handlegetbyidusingget) | **GET** /api/{context}/alerts/{id} | Get an alert by identifier
*AlertsApi* | [**handleGetListUsingGET**](docs/Api/AlertsApi.md#handlegetlistusingget) | **GET** /api/{context}/alerts | Get list of alerts
*AlertsApi* | [**handlePatchAnyUsingPATCH**](docs/Api/AlertsApi.md#handlepatchanyusingpatch) | **PATCH** /api/{context}/alerts/{id} | Update an alert
*AlertsApi* | [**handlePostAlertUsingPOST**](docs/Api/AlertsApi.md#handlepostalertusingpost) | **POST** /api/{context}/alerts | Create a new alert
*CapturesApi* | [**capturesForSigma**](docs/Api/CapturesApi.md#capturesforsigma) | **POST** /api/{context}/captures/sigma | Capture data from supplied Simga OIDC data
*CapturesApi* | [**capturesLastActive**](docs/Api/CapturesApi.md#captureslastactive) | **POST** /api/{context}/captures/active | Capture Last Active data
*CapturesApi* | [**getCaptures**](docs/Api/CapturesApi.md#getcaptures) | **GET** /api/{context}/captures | Get all captures for a user
*ExecutionsApi* | [**handlePatchUsingPATCH**](docs/Api/ExecutionsApi.md#handlepatchusingpatch) | **PATCH** /api/executions/{id} | handlePatch
*ExecutionsApi* | [**handlePostUsingPOST**](docs/Api/ExecutionsApi.md#handlepostusingpost) | **POST** /api/executions | handlePost
*FeaturesApi* | [**getFeatures**](docs/Api/FeaturesApi.md#getfeatures) | **GET** /api/{context}/features | Get all features for a context
*MarkersApi* | [**handleDeleteMarkerUsingDELETE**](docs/Api/MarkersApi.md#handledeletemarkerusingdelete) | **DELETE** /api/{context}/markers/{id} | Delete a marker
*MarkersApi* | [**handleGetMarkerUsingGET**](docs/Api/MarkersApi.md#handlegetmarkerusingget) | **GET** /api/{context}/markers/{id} | Get a marker by its ID
*MarkersApi* | [**handleGetMarkersUsingGET**](docs/Api/MarkersApi.md#handlegetmarkersusingget) | **GET** /api/{context}/markers | Get markers
*MarkersApi* | [**handlePostContentMarkerUsingPOST**](docs/Api/MarkersApi.md#handlepostcontentmarkerusingpost) | **POST** /api/{context}/markers | handlePostContentMarker
*PreferencesApi* | [**deletePreference**](docs/Api/PreferencesApi.md#deletepreference) | **DELETE** /api/{context}/preferences/{id} | Delete existing preference-setting
*PreferencesApi* | [**getPreferenceSetting1**](docs/Api/PreferencesApi.md#getpreferencesetting1) | **GET** /api/{context}/preferences/{id} | Retrieve existing preference-setting
*PreferencesApi* | [**getPreferences**](docs/Api/PreferencesApi.md#getpreferences) | **GET** /api/{context}/preferences | Get preferences for user
*PreferencesApi* | [**postPreferences**](docs/Api/PreferencesApi.md#postpreferences) | **POST** /api/{context}/preferences | Create (or update) preference-settings
*PreferencesApi* | [**updatePreferenceSetting**](docs/Api/PreferencesApi.md#updatepreferencesetting) | **PATCH** /api/{context}/preference-settings/{id} | Update an existing preference-setting
*SearchesApi* | [**deleteSearch**](docs/Api/SearchesApi.md#deletesearch) | **DELETE** /api/{context}/searches/{id} | Delete a saved-search
*SearchesApi* | [**handleGetSearchUsingGET**](docs/Api/SearchesApi.md#handlegetsearchusingget) | **GET** /api/{context}/searches/{id} | Get a saved-search
*SearchesApi* | [**handleGetSearchesUsingGET**](docs/Api/SearchesApi.md#handlegetsearchesusingget) | **GET** /api/{context}/searches | Get all saved-searches for the user
*SearchesApi* | [**handlePatchParamsUsingPATCH**](docs/Api/SearchesApi.md#handlepatchparamsusingpatch) | **PATCH** /api/{context}/searches/{id} | Update a saved-search
*SearchesApi* | [**handlePostUsingBodyUsingPOST**](docs/Api/SearchesApi.md#handlepostusingbodyusingpost) | **POST** /api/{context}/searches | Create a new saved-search
*TaggingsApi* | [**deleteTagging**](docs/Api/TaggingsApi.md#deletetagging) | **DELETE** /api/{context}/tagging/{id} | Delete tagging by Id
*TaggingsApi* | [**getTagging**](docs/Api/TaggingsApi.md#gettagging) | **GET** /api/{context}/tagging/{id} | Get tagging by ID
*TaggingsApi* | [**getTaggings**](docs/Api/TaggingsApi.md#gettaggings) | **GET** /api/{context}/taggings | Get all taggings for a user
*TagsApi* | [**createTag**](docs/Api/TagsApi.md#createtag) | **POST** /api/{context}/tags | Create a tag, optionally with content to tag
*TagsApi* | [**deleteTag**](docs/Api/TagsApi.md#deletetag) | **DELETE** /api/{context}/tags/{id} | Delete a tag
*TagsApi* | [**getTag**](docs/Api/TagsApi.md#gettag) | **GET** /api/{context}/tags/{id} | Get single Tag by its ID
*TagsApi* | [**getTagCounts**](docs/Api/TagsApi.md#gettagcounts) | **GET** /api/{context}/tag-counts | Get tag-counts for user
*TagsApi* | [**getTags**](docs/Api/TagsApi.md#gettags) | **GET** /api/{context}/tags | Get all tags for the specified user
*TagsApi* | [**updateTag**](docs/Api/TagsApi.md#updatetag) | **PATCH** /api/{context}/tags/{id} | Update existing tag
*WorkspacesApi* | [**handleCreateWorkspaceUsingParamsUsingPOST**](docs/Api/WorkspacesApi.md#handlecreateworkspaceusingparamsusingpost) | **POST** /api/{context}/workspaces | Create a new workspace
*WorkspacesApi* | [**handleDeleteWorkspaceUsingDELETE**](docs/Api/WorkspacesApi.md#handledeleteworkspaceusingdelete) | **DELETE** /api/{context}/workspaces/{handle} | Delete an existing workspace by its handle
*WorkspacesApi* | [**handleGetWorkspaceByEmailUsingGET**](docs/Api/WorkspacesApi.md#handlegetworkspacebyemailusingget) | **GET** /api/{context}/workspaces | Retrieve an existing workspace by its email
*WorkspacesApi* | [**handleGetWorkspaceByHandleUsingGET**](docs/Api/WorkspacesApi.md#handlegetworkspacebyhandleusingget) | **GET** /api/{context}/workspaces/{handle} | Retrieve an existing workspace by its handle


## Documentation For Models

 - [AlertResource](docs/Model/AlertResource.md)
 - [AlertResources](docs/Model/AlertResources.md)
 - [Api](docs/Model/Api.md)
 - [Campaign](docs/Model/Campaign.md)
 - [CaptureGroup](docs/Model/CaptureGroup.md)
 - [CategoryData](docs/Model/CategoryData.md)
 - [Config](docs/Model/Config.md)
 - [ContentData](docs/Model/ContentData.md)
 - [ContentResource](docs/Model/ContentResource.md)
 - [Email](docs/Model/Email.md)
 - [EmbeddedAlert](docs/Model/EmbeddedAlert.md)
 - [ExecutionData](docs/Model/ExecutionData.md)
 - [ExecutionResource](docs/Model/ExecutionResource.md)
 - [Feature](docs/Model/Feature.md)
 - [Link](docs/Model/Link.md)
 - [MarkedCategory](docs/Model/MarkedCategory.md)
 - [MarkedContent](docs/Model/MarkedContent.md)
 - [MarkedPage](docs/Model/MarkedPage.md)
 - [MarkerData](docs/Model/MarkerData.md)
 - [MarkerResource](docs/Model/MarkerResource.md)
 - [MarkerResources](docs/Model/MarkerResources.md)
 - [Option](docs/Model/Option.md)
 - [PageData](docs/Model/PageData.md)
 - [PageMetadata](docs/Model/PageMetadata.md)
 - [PreferenceProfile](docs/Model/PreferenceProfile.md)
 - [PreferenceProfileSetting](docs/Model/PreferenceProfileSetting.md)
 - [PreferenceSetting](docs/Model/PreferenceSetting.md)
 - [PreferenceSettingPatch](docs/Model/PreferenceSettingPatch.md)
 - [PreferenceSettingResource](docs/Model/PreferenceSettingResource.md)
 - [Preferences](docs/Model/Preferences.md)
 - [PreferencesData](docs/Model/PreferencesData.md)
 - [PreferencesResource](docs/Model/PreferencesResource.md)
 - [ProcessedAlert](docs/Model/ProcessedAlert.md)
 - [Profile](docs/Model/Profile.md)
 - [Recipient](docs/Model/Recipient.md)
 - [SearchData](docs/Model/SearchData.md)
 - [SearchResource](docs/Model/SearchResource.md)
 - [SearchResources](docs/Model/SearchResources.md)
 - [Sender](docs/Model/Sender.md)
 - [Service](docs/Model/Service.md)
 - [ServiceFeature](docs/Model/ServiceFeature.md)
 - [ServiceFeatures](docs/Model/ServiceFeatures.md)
 - [Settings](docs/Model/Settings.md)
 - [Site](docs/Model/Site.md)
 - [State](docs/Model/State.md)
 - [Substitution](docs/Model/Substitution.md)
 - [TagCount](docs/Model/TagCount.md)
 - [TagCountResource](docs/Model/TagCountResource.md)
 - [TagData](docs/Model/TagData.md)
 - [TagResource](docs/Model/TagResource.md)
 - [TagResources](docs/Model/TagResources.md)
 - [TaggedContent](docs/Model/TaggedContent.md)
 - [TaggingResource](docs/Model/TaggingResource.md)
 - [TaggingResources](docs/Model/TaggingResources.md)
 - [Template](docs/Model/Template.md)
 - [URI](docs/Model/URI.md)
 - [Venue](docs/Model/Venue.md)
 - [Workspace](docs/Model/Workspace.md)
 - [WorkspaceData](docs/Model/WorkspaceData.md)
 - [WorkspaceQuery](docs/Model/WorkspaceQuery.md)
 - [WorkspaceResource](docs/Model/WorkspaceResource.md)
 - [Zetting](docs/Model/Zetting.md)


## Documentation For Authorization

 All endpoints do not require authorization.


## Author

cjurney@highwire.org


