<?php
/**
 * PreferenceProfile
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
 * PreferenceProfile Class Doc Comment
 *
 * @category Class
 * @package  PersonalizationClient
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class PreferenceProfile implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'PreferenceProfile';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'created' => '\DateTime',
        'id' => 'int',
        'label' => 'string',
        'name' => 'string',
        'selector' => 'string',
        'settings' => '\PersonalizationClient\Model\PreferenceProfileSetting[]',
        'sort_order' => 'int',
        'status' => 'string',
        'status_updated' => '\DateTime',
        'updated' => '\DateTime'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'created' => 'date-time',
        'id' => 'int64',
        'label' => null,
        'name' => null,
        'selector' => null,
        'settings' => null,
        'sort_order' => 'int32',
        'status' => null,
        'status_updated' => 'date-time',
        'updated' => 'date-time'
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
        'created' => 'created',
        'id' => 'id',
        'label' => 'label',
        'name' => 'name',
        'selector' => 'selector',
        'settings' => 'settings',
        'sort_order' => 'sort_order',
        'status' => 'status',
        'status_updated' => 'statusUpdated',
        'updated' => 'updated'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'created' => 'setCreated',
        'id' => 'setId',
        'label' => 'setLabel',
        'name' => 'setName',
        'selector' => 'setSelector',
        'settings' => 'setSettings',
        'sort_order' => 'setSortOrder',
        'status' => 'setStatus',
        'status_updated' => 'setStatusUpdated',
        'updated' => 'setUpdated'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'created' => 'getCreated',
        'id' => 'getId',
        'label' => 'getLabel',
        'name' => 'getName',
        'selector' => 'getSelector',
        'settings' => 'getSettings',
        'sort_order' => 'getSortOrder',
        'status' => 'getStatus',
        'status_updated' => 'getStatusUpdated',
        'updated' => 'getUpdated'
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

    const STATUS_INCUBATING = 'incubating';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_ARCHIVED = 'archived';
    

    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getStatusAllowableValues()
    {
        return [
            self::STATUS_INCUBATING,
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
            self::STATUS_ARCHIVED,
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
        $this->container['created'] = isset($data['created']) ? $data['created'] : null;
        $this->container['id'] = isset($data['id']) ? $data['id'] : null;
        $this->container['label'] = isset($data['label']) ? $data['label'] : null;
        $this->container['name'] = isset($data['name']) ? $data['name'] : null;
        $this->container['selector'] = isset($data['selector']) ? $data['selector'] : null;
        $this->container['settings'] = isset($data['settings']) ? $data['settings'] : null;
        $this->container['sort_order'] = isset($data['sort_order']) ? $data['sort_order'] : null;
        $this->container['status'] = isset($data['status']) ? $data['status'] : null;
        $this->container['status_updated'] = isset($data['status_updated']) ? $data['status_updated'] : null;
        $this->container['updated'] = isset($data['updated']) ? $data['updated'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['created'] === null) {
            $invalidProperties[] = "'created' can't be null";
        }
        if (!is_null($this->container['label']) && (mb_strlen($this->container['label']) > 1024)) {
            $invalidProperties[] = "invalid value for 'label', the character length must be smaller than or equal to 1024.";
        }

        if (!is_null($this->container['label']) && (mb_strlen($this->container['label']) < 0)) {
            $invalidProperties[] = "invalid value for 'label', the character length must be bigger than or equal to 0.";
        }

        if ($this->container['name'] === null) {
            $invalidProperties[] = "'name' can't be null";
        }
        if ((mb_strlen($this->container['name']) > 80)) {
            $invalidProperties[] = "invalid value for 'name', the character length must be smaller than or equal to 80.";
        }

        if ((mb_strlen($this->container['name']) < 3)) {
            $invalidProperties[] = "invalid value for 'name', the character length must be bigger than or equal to 3.";
        }

        if ($this->container['selector'] === null) {
            $invalidProperties[] = "'selector' can't be null";
        }
        if ((mb_strlen($this->container['selector']) > 32)) {
            $invalidProperties[] = "invalid value for 'selector', the character length must be smaller than or equal to 32.";
        }

        if ((mb_strlen($this->container['selector']) < 3)) {
            $invalidProperties[] = "invalid value for 'selector', the character length must be bigger than or equal to 3.";
        }

        if ($this->container['status'] === null) {
            $invalidProperties[] = "'status' can't be null";
        }
        $allowedValues = $this->getStatusAllowableValues();
        if (!is_null($this->container['status']) && !in_array($this->container['status'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'status', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['status_updated'] === null) {
            $invalidProperties[] = "'status_updated' can't be null";
        }
        if ($this->container['updated'] === null) {
            $invalidProperties[] = "'updated' can't be null";
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
     * Gets created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->container['created'];
    }

    /**
     * Sets created
     *
     * @param \DateTime $created The date & time the entry was created.
     *
     * @return $this
     */
    public function setCreated($created)
    {
        $this->container['created'] = $created;

        return $this;
    }

    /**
     * Gets id
     *
     * @return int
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id
     *
     * @param int $id id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

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
     * @param string $label Overall label for a set of preferences.
     *
     * @return $this
     */
    public function setLabel($label)
    {
        if (!is_null($label) && (mb_strlen($label) > 1024)) {
            throw new \InvalidArgumentException('invalid length for $label when calling PreferenceProfile., must be smaller than or equal to 1024.');
        }
        if (!is_null($label) && (mb_strlen($label) < 0)) {
            throw new \InvalidArgumentException('invalid length for $label when calling PreferenceProfile., must be bigger than or equal to 0.');
        }

        $this->container['label'] = $label;

        return $this;
    }

    /**
     * Gets name
     *
     * @return string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name
     *
     * @param string $name The name of the profile, eg, 'Marketing consent.
     *
     * @return $this
     */
    public function setName($name)
    {
        if ((mb_strlen($name) > 80)) {
            throw new \InvalidArgumentException('invalid length for $name when calling PreferenceProfile., must be smaller than or equal to 80.');
        }
        if ((mb_strlen($name) < 3)) {
            throw new \InvalidArgumentException('invalid length for $name when calling PreferenceProfile., must be bigger than or equal to 3.');
        }

        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets selector
     *
     * @return string
     */
    public function getSelector()
    {
        return $this->container['selector'];
    }

    /**
     * Sets selector
     *
     * @param string $selector The selector of the profile, eg, 'marketing-consent.
     *
     * @return $this
     */
    public function setSelector($selector)
    {
        if ((mb_strlen($selector) > 32)) {
            throw new \InvalidArgumentException('invalid length for $selector when calling PreferenceProfile., must be smaller than or equal to 32.');
        }
        if ((mb_strlen($selector) < 3)) {
            throw new \InvalidArgumentException('invalid length for $selector when calling PreferenceProfile., must be bigger than or equal to 3.');
        }

        $this->container['selector'] = $selector;

        return $this;
    }

    /**
     * Gets settings
     *
     * @return \PersonalizationClient\Model\PreferenceProfileSetting[]
     */
    public function getSettings()
    {
        return $this->container['settings'];
    }

    /**
     * Sets settings
     *
     * @param \PersonalizationClient\Model\PreferenceProfileSetting[] $settings settings
     *
     * @return $this
     */
    public function setSettings($settings)
    {
        $this->container['settings'] = $settings;

        return $this;
    }

    /**
     * Gets sort_order
     *
     * @return int
     */
    public function getSortOrder()
    {
        return $this->container['sort_order'];
    }

    /**
     * Sets sort_order
     *
     * @param int $sort_order sort_order
     *
     * @return $this
     */
    public function setSortOrder($sort_order)
    {
        $this->container['sort_order'] = $sort_order;

        return $this;
    }

    /**
     * Gets status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     *
     * @param string $status Controls the active record in the environment.
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $allowedValues = $this->getStatusAllowableValues();
        if (!in_array($status, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'status', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets status_updated
     *
     * @return \DateTime
     */
    public function getStatusUpdated()
    {
        return $this->container['status_updated'];
    }

    /**
     * Sets status_updated
     *
     * @param \DateTime $status_updated The date & time the status changed.
     *
     * @return $this
     */
    public function setStatusUpdated($status_updated)
    {
        $this->container['status_updated'] = $status_updated;

        return $this;
    }

    /**
     * Gets updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->container['updated'];
    }

    /**
     * Sets updated
     *
     * @param \DateTime $updated The date & time the entry was last updated.
     *
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->container['updated'] = $updated;

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


