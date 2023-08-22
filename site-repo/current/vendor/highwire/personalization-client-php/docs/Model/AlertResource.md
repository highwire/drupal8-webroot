# AlertResource

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**corpus** | [**\PersonalizationClient\Model\ContentResource**](ContentResource.md) | the body of content we&#39;re watching for changes. | [optional] 
**created** | **string** | The date &amp; time the entity was created. | 
**delivery** | **string** | How the alerts are to be delivered to the recipient. | 
**feature** | [**\PersonalizationClient\Model\Feature**](Feature.md) | Feature definitions &amp; etting values for the specific alert type. | [optional] 
**frequency** | **string** | Controlled intervals aligned with the corresponding downstream (Dominos) job. When the reader is in control, the value is &lt;em&gt;interval&lt;/em&gt; and the alert&#39;s periodicity is controlled by &lt;em&gt;interval-days&lt;/em&gt;. | 
**interval_days** | **int** | When frequency is &lt;em&gt;interval&lt;/em&gt;, this is the number of days between executions of the alert. Eg, a value of 1 could lead to a daily alert whereas 7 could lead to a weekly alert. | [optional] 
**key** | **int** | The numeric key assigned by the JPA repository. | [optional] 
**label** | **string** | Alert label. | 
**last_result** | **string** | The date &amp; time the entity was last ran to successful completion. | [optional] 
**last_run** | **string** | The date &amp; time the entity was last executed by system. | 
**saved_search** | [**\PersonalizationClient\Model\Feature**](Feature.md) | Setting values for a saved-search. | [optional] 
**status** | **string** | Alert status. | 
**status_updated** | **string** | The date &amp; time the &lt;code&gt;status&lt;/code&gt; was changed. | 
**tracking** | [**\PersonalizationClient\Model\ContentResource**](ContentResource.md) | The specific resource we&#39;re watching for changes. | [optional] 
**type** | **string** | Alert type. | 
**updated** | **string** | The date &amp; time the entity was updated. | 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


