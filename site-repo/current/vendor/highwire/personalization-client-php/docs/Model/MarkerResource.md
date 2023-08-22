# MarkerResource

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**feature** | [**\PersonalizationClient\Model\Feature**](Feature.md) |  | [optional] 
**owner** | [**\PersonalizationClient\Model\WorkspaceResource**](WorkspaceResource.md) |  | [optional] 
**id** | **int** | The numeric key assigned by the JPA repository. | 
**created** | **string** | The date &amp; time the resource was created. | 
**updated** | **string** | The date &amp; time the resource was updated. | 
**label** | **string** | To aid in recall when presented with a flat list of saved search. | 
**category** | [**\PersonalizationClient\Model\MarkedCategory**](MarkedCategory.md) | The marked category (scheme &amp; term). | [optional] 
**content** | [**\PersonalizationClient\Model\MarkedContent**](MarkedContent.md) | The marked content (Atom). | [optional] 
**page** | [**\PersonalizationClient\Model\MarkedPage**](MarkedPage.md) | The marked page (URL). | 
**notes** | **string** | To remind readers why the search was of interest or summary of results, etc. | [optional] 
**links** | [**\PersonalizationClient\Model\Link[]**](Link.md) | Hyperlinks to related resources. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


