# SearchData

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**label** | **string** | To aid in recall when presented with a flat list of saved search. | 
**description** | **string** | A digest of the search supplied by the site of the form &#39;Your search for....&#39; | 
**query** | **string** | URL-style &#39;key&#x3D;value&#39; search store as raw text, ie, not URL-encoded. By convention this is not a full URL (one the encompasses the origin-server), nor even one rooted in the search service path but instead is only the portion of the URL that would follow the &#39;?&#39; in the fully-formed URL. | [optional] 
**results** | **string** | URL back to the site that executes the search and lands the user on the result page. | [optional] 
**query_json** | **string** | A JSON object encapsulating a query that may be POST&#39;d to a search service (point) for execution. | [optional] 
**query_xml** | **string** | An XML document encapsulating a query that may be POST&#39;d to a search service (point) for execution. | [optional] 
**notes** | **string** | To remind readers why the search was of interest or summary of results, etc. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


