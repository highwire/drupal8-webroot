# TagResource

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**feature** | [**\PersonalizationClient\Model\Feature**](Feature.md) |  | [optional] 
**id** | **int** | The numeric key assigned by the JPA repository. | 
**created** | **string** | The date &amp; time the resource was created. | 
**updated** | **string** | The date &amp; time the resource was updated. | 
**last_used** | **string** | The date &amp; time the tag was last used. | 
**label** | **string** | The readable handle for the Tag, must be unique within the Workspace. | 
**notes** | **string** | To remind readers why the search was of interest or summary of results, etc. | [optional] 
**taggings** | [**\PersonalizationClient\Model\TaggingResource[]**](TaggingResource.md) | THe just-tagged content, if any. | [optional] 
**links** | [**\PersonalizationClient\Model\Link[]**](Link.md) | Hyperlinks to related resources. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


