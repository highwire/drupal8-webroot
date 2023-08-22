# PreferenceProfileSetting

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**created** | [**\DateTime**](\DateTime.md) | The date &amp; time the entry was created. | 
**default_value** | **string** | A default value for the setting. | [optional] 
**id** | **int** |  | [optional] 
**label** | **string** | The setting label, eg, &#39;Please answer yes or no. | 
**max_length** | **int** | For textual input, the maximum length of input allowed, eg, &#39;256&#39;. | [optional] 
**options** | **map[string,string]** |  | [optional] 
**preference_profile** | [**\PersonalizationClient\Model\PreferenceProfile**](PreferenceProfile.md) |  | [optional] 
**profile** | [**\PersonalizationClient\Model\PreferenceProfile**](PreferenceProfile.md) |  | [optional] 
**prompt** | **string** | A hint / tool-tip for the setting. | [optional] 
**required** | **bool** |  | [optional] 
**selector** | **string** | The name of the setting, eg, &#39;does-consent. | 
**sort_order** | **int** |  | [optional] 
**type** | **string** | Type of input-control, eg, &#39;toggle&#39; or &#39;single&#39; | 
**updated** | [**\DateTime**](\DateTime.md) | The date &amp; time the entry was last updated. | 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


