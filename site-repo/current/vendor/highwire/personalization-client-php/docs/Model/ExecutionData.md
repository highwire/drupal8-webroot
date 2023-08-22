# ExecutionData

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**alert_context** | **string** |  | 
**alert_context** | **string** | The context for which we should find an alert prototype. | 
**alert_frequency** | **string** | Publisher-specified and controlled intervals aligned with the corresponding downstream (Dominos) job. When the reader is in control, the value is INTERVAL and the alert&#39;s periodicity is controlled by intervalDays. | [optional] 
**alert_type** | **string** | A context-specific type of alert, possibly custom for that publisher, eg, toc. | 
**atoms** | [**\PersonalizationClient\Model\URI[]**](URI.md) | One or more Atom URIs for the specific content that comprise this alert execution. | [optional] 
**corpus** | **string** | The content (possibly the corpus corpus) for which we should find an alert prototype. | [optional] 
**job_id** | **string** | Optional identifier that locates the job in the calling system. | [optional] 
**label** | **string** | Optional name of the job in the calling system. | [optional] 
**alert_type** | **string** |  | 
**alert_frequency** | **string** |  | [optional] 
**job_id** | **string** |  | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


