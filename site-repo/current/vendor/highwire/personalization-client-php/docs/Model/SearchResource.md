# SearchResource

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**feature** | [**\PersonalizationClient\Model\Feature**](Feature.md) |  | [optional] 
**links** | [**\PersonalizationClient\Model\Link[]**](Link.md) | Hyperlinks to related resources. | [optional] 
**owner** | [**\PersonalizationClient\Model\Workspace**](Workspace.md) |  | [optional] 
**id** | **int** | The numeric key assigned by the JPA repository. | 
**created** | **string** | The date &amp; time the resource was created. | 
**results** | **string** | URL back to the site that executes the search and lands the user on the result page. | [optional] 
**updated** | **string** | The date &amp; time the resource was updated. | 
**label** | **string** | To aid in recall when presented with a flat list of saved search. | 
**description** | **string** | A digest of the search supplied by the site of the form &#39;Your search for....&#39; | 
**query_doc** | **string** | An XML document or JSON object encapsulating a query that may be POST&#39;d to a search service (point) for execution. | [optional] 
**query_params** | **string** | URL-style &#39;key&#x3D;value&#39; search store as raw text, ie, not URL-encoded. By convention this is not a full URL (one the encompasses the origin-server), nor even one rooted in the search service path but instead is only the portion of the URL that would follow the &#39;?&#39; in the fully-formed URL. | [optional] 
**notes** | **string** | To remind readers why the search was of interest or summary of results, etc. | [optional] 
**alert** | [**\PersonalizationClient\Model\EmbeddedAlert**](EmbeddedAlert.md) | Active or paused alert. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


