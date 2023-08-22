# Zetting

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**selector** | **string** | The name of the setting, eg, &#39;does-consent. | 
**label** | **string** | The setting label, eg, &#39;Please answer yes or no. | 
**prompt** | **string** | A hint / tool-tip for the setting. | [optional] 
**type** | **string** | Type of input-control, eg, &#39;toggle&#39; or &#39;single&#39; | 
**max_length** | **int** | For textual input, the maximum length of input allowed, eg, &#39;256&#39;. | [optional] 
**default** | **string** | A default value for the setting. | [optional] 
**options** | **map[string,string]** | Valid choices for the setting. | [optional] 
**required** | **bool** | A selection is required. | [optional] 
**preference** | [**\PersonalizationClient\Model\PreferenceSettingResource**](PreferenceSettingResource.md) | User selection. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


