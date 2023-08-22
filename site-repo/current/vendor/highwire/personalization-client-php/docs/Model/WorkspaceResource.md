# WorkspaceResource

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**links** | [**\PersonalizationClient\Model\Link[]**](Link.md) |  | [optional] 
**context** | **string** | The application context for this service, eg, &lt;em&gt;sgrworks&lt;/em&gt;. A single publisher may have more than one application (or set of applications like JCore) using a platform service like Personalization and so the &lt;strong&gt;context&lt;/strong&gt; semantic scopes the service data in a way that the calling application can control. | 
**handle** | **string** | A (hash) value unique within the context that unambiguously associates the workspace with its owner. For SAMS Sigma backed application contexts, murmur3 (32-bit, 0 seed) hash of the individual user&#39;s email address is the expected choice. | 
**email** | **string** | For SAMS Sigma backed application contexts, the individual user&#39;s email address is the expected choice, eg, cjurney@gmail.com. | 
**name** | **string** | A value used for labeling workspace objects, might be an email address, might be the owner&#39;s name. | 
**database_id** | **int** | The numeric key assigned by the JPA repository. | 
**created** | **string** | The date &amp; time the workspace was created. | 
**updated** | **string** | The date &amp; time the workspace was updated. | 
**last_used** | **string** | The date &amp; time the workspace was last used in its associated context. | 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


