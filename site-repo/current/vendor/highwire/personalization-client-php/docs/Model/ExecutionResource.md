# ExecutionResource

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**links** | [**\PersonalizationClient\Model\Link[]**](Link.md) |  | [optional] 
**id** | **int** | The numeric key assigned by the JPA repository. | 
**created** | **string** | The date &amp; time the resource was created. | 
**updated** | **string** | The date &amp; time the resource was updated. | 
**completed** | **string** | The date &amp; time the execution completed. | [optional] 
**status** | **string** | The final status of completed execution. | [optional] 
**label** | **string** | To aid in recall when presented with a list of executions. | 
**job_id** | **string** | Optional identifier that locates the job in the calling system. | [optional] 
**config** | [**\PersonalizationClient\Model\Config**](Config.md) | Configuration for the runtime. | [optional] 
**processed** | [**\PersonalizationClient\Model\ProcessedAlert[]**](ProcessedAlert.md) | Alerts processed. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


