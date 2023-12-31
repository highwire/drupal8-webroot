<?php
/**
 * ExecutionData
 *
 * PHP version 5
 *
 * @category Class
 * @package  PersonalizationClient
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * Personalization API
 *
 * Craig's <strong>best</strong> attempt at generated API documentation for web use.
 *
 * OpenAPI spec version: 1.0.0
 * Contact: cjurney@highwire.org
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 2.4.0-SNAPSHOT
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace PersonalizationClient\Model;

use \ArrayAccess;
use \PersonalizationClient\ObjectSerializer;

/**
 * ExecutionData Class Doc Comment
 *
 * @category Class
 * @package  PersonalizationClient
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class ExecutionData implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'ExecutionData';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'alert_context' => 'string',
        'alert_context' => 'string',
        'alert_frequency' => 'string',
        'alert_type' => 'string',
        'atoms' => '\PersonalizationClient\Model\URI[]',
        'corpus' => 'string',
        'job_id' => 'string',
        'label' => 'string',
        'alert_type' => 'string',
        'alert_frequency' => 'string',
        'job_id' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'alert_context' => null,
        'alert_context' => null,
        'alert_frequency' => null,
        'alert_type' => null,
        'atoms' => null,
        'corpus' => null,
        'job_id' => null,
        'label' => null,
        'alert_type' => null,
        'alert_frequency' => null,
        'job_id' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'alert_context' => 'alert-context',
        'alert_context' => 'alertContext',
        'alert_frequency' => 'alertFrequency',
        'alert_type' => 'alertType',
        'atoms' => 'atoms',
        'corpus' => 'corpus',
        'job_id' => 'jobId',
        'label' => 'label',
        'alert_type' => 'alert-type',
        'alert_frequency' => 'alert-frequency',
        'job_id' => 'job-id'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'alert_context' => 'setAlertContext',
        'alert_context' => 'setAlertContext',
        'alert_frequency' => 'setAlertFrequency',
        'alert_type' => 'setAlertType',
        'atoms' => 'setAtoms',
        'corpus' => 'setCorpus',
        'job_id' => 'setJobId',
        'label' => 'setLabel',
        'alert_type' => 'setAlertType',
        'alert_frequency' => 'setAlertFrequency',
        'job_id' => 'setJobId'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'alert_context' => 'getAlertContext',
        'alert_context' => 'getAlertContext',
        'alert_frequency' => 'getAlertFrequency',
        'alert_type' => 'getAlertType',
        'atoms' => 'getAtoms',
        'corpus' => 'getCorpus',
        'job_id' => 'getJobId',
        'label' => 'getLabel',
        'alert_type' => 'getAlertType',
        'alert_frequency' => 'getAlertFrequency',
        'job_id' => 'getJobId'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$swaggerModelName;
    }

    const ALERT_FREQUENCY_EVENT = 'event';
    const ALERT_FREQUENCY_DAILY = 'daily';
    const ALERT_FREQUENCY_WEEKLY = 'weekly';
    const ALERT_FREQUENCY_BIWEEKLY = 'biweekly';
    const ALERT_FREQUENCY_MONTHLY = 'monthly';
    const ALERT_FREQUENCY_YEARLY = 'yearly';
    const ALERT_FREQUENCY_INTERVAL = 'interval';
    const ALERT_FREQUENCY_EVENT = 'event';
    const ALERT_FREQUENCY_DAILY = 'daily';
    const ALERT_FREQUENCY_WEEKLY = 'weekly';
    const ALERT_FREQUENCY_BIWEEKLY = 'biweekly';
    const ALERT_FREQUENCY_MONTHLY = 'monthly';
    const ALERT_FREQUENCY_YEARLY = 'yearly';
    const ALERT_FREQUENCY_INTERVAL = 'interval';
    

    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getAlertFrequencyAllowableValues()
    {
        return [
            self::ALERT_FREQUENCY_EVENT,
            self::ALERT_FREQUENCY_DAILY,
            self::ALERT_FREQUENCY_WEEKLY,
            self::ALERT_FREQUENCY_BIWEEKLY,
            self::ALERT_FREQUENCY_MONTHLY,
            self::ALERT_FREQUENCY_YEARLY,
            self::ALERT_FREQUENCY_INTERVAL,
        ];
    }
    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getAlertFrequencyAllowableValues()
    {
        return [
            self::ALERT_FREQUENCY_EVENT,
            self::ALERT_FREQUENCY_DAILY,
            self::ALERT_FREQUENCY_WEEKLY,
            self::ALERT_FREQUENCY_BIWEEKLY,
            self::ALERT_FREQUENCY_MONTHLY,
            self::ALERT_FREQUENCY_YEARLY,
            self::ALERT_FREQUENCY_INTERVAL,
        ];
    }
    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['alert_context'] = isset($data['alert_context']) ? $data['alert_context'] : null;
        $this->container['alert_context'] = isset($data['alert_context']) ? $data['alert_context'] : null;
        $this->container['alert_frequency'] = isset($data['alert_frequency']) ? $data['alert_frequency'] : null;
        $this->container['alert_type'] = isset($data['alert_type']) ? $data['alert_type'] : null;
        $this->container['atoms'] = isset($data['atoms']) ? $data['atoms'] : null;
        $this->container['corpus'] = isset($data['corpus']) ? $data['corpus'] : null;
        $this->container['job_id'] = isset($data['job_id']) ? $data['job_id'] : null;
        $this->container['label'] = isset($data['label']) ? $data['label'] : null;
        $this->container['alert_type'] = isset($data['alert_type']) ? $data['alert_type'] : null;
        $this->container['alert_frequency'] = isset($data['alert_frequency']) ? $data['alert_frequency'] : null;
        $this->container['job_id'] = isset($data['job_id']) ? $data['job_id'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['alert_context'] === null) {
            $invalidProperties[] = "'alert_context' can't be null";
        }
        if ($this->container['alert_context'] === null) {
            $invalidProperties[] = "'alert_context' can't be null";
        }
        $allowedValues = $this->getAlertFrequencyAllowableValues();
        if (!is_null($this->container['alert_frequency']) && !in_array($this->container['alert_frequency'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'alert_frequency', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['alert_type'] === null) {
            $invalidProperties[] = "'alert_type' can't be null";
        }
        if (!is_null($this->container['job_id']) && (mb_strlen($this->container['job_id']) > 256)) {
            $invalidProperties[] = "invalid value for 'job_id', the character length must be smaller than or equal to 256.";
        }

        if (!is_null($this->container['job_id']) && (mb_strlen($this->container['job_id']) < 0)) {
            $invalidProperties[] = "invalid value for 'job_id', the character length must be bigger than or equal to 0.";
        }

        if (!is_null($this->container['label']) && (mb_strlen($this->container['label']) > 256)) {
            $invalidProperties[] = "invalid value for 'label', the character length must be smaller than or equal to 256.";
        }

        if (!is_null($this->container['label']) && (mb_strlen($this->container['label']) < 0)) {
            $invalidProperties[] = "invalid value for 'label', the character length must be bigger than or equal to 0.";
        }

        if ($this->container['alert_type'] === null) {
            $invalidProperties[] = "'alert_type' can't be null";
        }
        $allowedValues = $this->getAlertFrequencyAllowableValues();
        if (!is_null($this->container['alert_frequency']) && !in_array($this->container['alert_frequency'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'alert_frequency', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets alert_context
     *
     * @return string
     */
    public function getAlertContext()
    {
        return $this->container['alert_context'];
    }

    /**
     * Sets alert_context
     *
     * @param string $alert_context alert_context
     *
     * @return $this
     */
    public function setAlertContext($alert_context)
    {
        $this->container['alert_context'] = $alert_context;

        return $this;
    }

    /**
     * Gets alert_context
     *
     * @return string
     */
    public function getAlertContext()
    {
        return $this->container['alert_context'];
    }

    /**
     * Sets alert_context
     *
     * @param string $alert_context The context for which we should find an alert prototype.
     *
     * @return $this
     */
    public function setAlertContext($alert_context)
    {
        $this->container['alert_context'] = $alert_context;

        return $this;
    }

    /**
     * Gets alert_frequency
     *
     * @return string
     */
    public function getAlertFrequency()
    {
        return $this->container['alert_frequency'];
    }

    /**
     * Sets alert_frequency
     *
     * @param string $alert_frequency Publisher-specified and controlled intervals aligned with the corresponding downstream (Dominos) job. When the reader is in control, the value is INTERVAL and the alert's periodicity is controlled by intervalDays.
     *
     * @return $this
     */
    public function setAlertFrequency($alert_frequency)
    {
        $allowedValues = $this->getAlertFrequencyAllowableValues();
        if (!is_null($alert_frequency) && !in_array($alert_frequency, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'alert_frequency', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['alert_frequency'] = $alert_frequency;

        return $this;
    }

    /**
     * Gets alert_type
     *
     * @return string
     */
    public function getAlertType()
    {
        return $this->container['alert_type'];
    }

    /**
     * Sets alert_type
     *
     * @param string $alert_type A context-specific type of alert, possibly custom for that publisher, eg, toc.
     *
     * @return $this
     */
    public function setAlertType($alert_type)
    {
        $this->container['alert_type'] = $alert_type;

        return $this;
    }

    /**
     * Gets atoms
     *
     * @return \PersonalizationClient\Model\URI[]
     */
    public function getAtoms()
    {
        return $this->container['atoms'];
    }

    /**
     * Sets atoms
     *
     * @param \PersonalizationClient\Model\URI[] $atoms One or more Atom URIs for the specific content that comprise this alert execution.
     *
     * @return $this
     */
    public function setAtoms($atoms)
    {
        $this->container['atoms'] = $atoms;

        return $this;
    }

    /**
     * Gets corpus
     *
     * @return string
     */
    public function getCorpus()
    {
        return $this->container['corpus'];
    }

    /**
     * Sets corpus
     *
     * @param string $corpus The content (possibly the corpus corpus) for which we should find an alert prototype.
     *
     * @return $this
     */
    public function setCorpus($corpus)
    {
        $this->container['corpus'] = $corpus;

        return $this;
    }

    /**
     * Gets job_id
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->container['job_id'];
    }

    /**
     * Sets job_id
     *
     * @param string $job_id Optional identifier that locates the job in the calling system.
     *
     * @return $this
     */
    public function setJobId($job_id)
    {
        if (!is_null($job_id) && (mb_strlen($job_id) > 256)) {
            throw new \InvalidArgumentException('invalid length for $job_id when calling ExecutionData., must be smaller than or equal to 256.');
        }
        if (!is_null($job_id) && (mb_strlen($job_id) < 0)) {
            throw new \InvalidArgumentException('invalid length for $job_id when calling ExecutionData., must be bigger than or equal to 0.');
        }

        $this->container['job_id'] = $job_id;

        return $this;
    }

    /**
     * Gets label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->container['label'];
    }

    /**
     * Sets label
     *
     * @param string $label Optional name of the job in the calling system.
     *
     * @return $this
     */
    public function setLabel($label)
    {
        if (!is_null($label) && (mb_strlen($label) > 256)) {
            throw new \InvalidArgumentException('invalid length for $label when calling ExecutionData., must be smaller than or equal to 256.');
        }
        if (!is_null($label) && (mb_strlen($label) < 0)) {
            throw new \InvalidArgumentException('invalid length for $label when calling ExecutionData., must be bigger than or equal to 0.');
        }

        $this->container['label'] = $label;

        return $this;
    }

    /**
     * Gets alert_type
     *
     * @return string
     */
    public function getAlertType()
    {
        return $this->container['alert_type'];
    }

    /**
     * Sets alert_type
     *
     * @param string $alert_type alert_type
     *
     * @return $this
     */
    public function setAlertType($alert_type)
    {
        $this->container['alert_type'] = $alert_type;

        return $this;
    }

    /**
     * Gets alert_frequency
     *
     * @return string
     */
    public function getAlertFrequency()
    {
        return $this->container['alert_frequency'];
    }

    /**
     * Sets alert_frequency
     *
     * @param string $alert_frequency alert_frequency
     *
     * @return $this
     */
    public function setAlertFrequency($alert_frequency)
    {
        $allowedValues = $this->getAlertFrequencyAllowableValues();
        if (!is_null($alert_frequency) && !in_array($alert_frequency, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'alert_frequency', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['alert_frequency'] = $alert_frequency;

        return $this;
    }

    /**
     * Gets job_id
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->container['job_id'];
    }

    /**
     * Sets job_id
     *
     * @param string $job_id job_id
     *
     * @return $this
     */
    public function setJobId($job_id)
    {
        $this->container['job_id'] = $job_id;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(
                ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


