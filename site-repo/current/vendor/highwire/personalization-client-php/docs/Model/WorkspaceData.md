# WorkspaceData

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**email** | **string** | For SAMS Sigma backed application contexts, the individual user&#39;s email address is the expected choice, eg, pookie@gmail.com. | 
**handle** | **string** | A (hash) value unique within the context that unambiguously associates the workspace with its owner. For SAMS Sigma backed application contexts, murmur3 (32-bit, 0 seed) hash of the individual user&#39;s email address is the expected choice, but any other 8-character hexadecimal value is acceptable, eg, e40b7df3 | [optional] 
**name** | **string** | A value used for labeling workspace objects, might be an email address, might be the owner&#39;s name. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


