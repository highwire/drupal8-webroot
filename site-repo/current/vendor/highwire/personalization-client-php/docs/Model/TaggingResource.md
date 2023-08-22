# TaggingResource

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **int** | The numeric key assigned by the JPA repository. | 
**created** | **string** | The date &amp; time the resource was created. | 
**tag** | **string** | This tagging. | [optional] 
**tagged** | [**\PersonalizationClient\Model\TaggedContent**](TaggedContent.md) | The tagged resource, eg, content. | 
**other_tags** | **string[]** | Other tags for this content. | [optional] 
**links** | [**\PersonalizationClient\Model\Link[]**](Link.md) | Hyperlinks to related resources. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


