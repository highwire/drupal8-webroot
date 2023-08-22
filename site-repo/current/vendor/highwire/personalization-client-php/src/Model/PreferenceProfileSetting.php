<?php
/**
 * PreferenceProfileSetting
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
 * PreferenceProfileSetting Class Doc Comment
 *
 * @category Class
 * @package  PersonalizationClient
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class PreferenceProfileSetting implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'PreferenceProfileSetting';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'created' => '\DateTime',
        'default_value' => 'string',
        'id' => 'int',
        'label' => 'string',
        'max_length' => 'int',
        'options' => 'map[string,string]',
        'preference_profile' => '\PersonalizationClient\Model\PreferenceProfile',
        'profile' => '\PersonalizationClient\Model\PreferenceProfile',
        'prompt' => 'string',
        'required' => 'bool',
        'selector' => 'string',
        'sort_order' => 'int',
        'type' => 'string',
        'updated' => '\DateTime'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'created' => 'date-time',
        'default_value' => null,
        'id' => 'int64',
        'label' => null,
        'max_length' => 'int32',
        'options' => null,
        'preference_profile' => null,
        'profile' => null,
        'prompt' => null,
        'required' => null,
        'selector' => null,
        'sort_order' => 'int32',
        'type' => null,
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
        'default_value' => 'defaultValue',
        'id' => 'id',
        'label' => 'label',
        'max_length' => 'maxLength',
        'options' => 'options',
        'preference_profile' => 'preferenceProfile',
        'profile' => 'profile',
        'prompt' => 'prompt',
        'required' => 'required',
        'selector' => 'selector',
        'sort_order' => 'sort_order',
        'type' => 'type',
        'updated' => 'updated'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'created' => 'setCreated',
        'default_value' => 'setDefaultValue',
        'id' => 'setId',
        'label' => 'setLabel',
        'max_length' => 'setMaxLength',
        'options' => 'setOptions',
        'preference_profile' => 'setPreferenceProfile',
        'profile' => 'setProfile',
        'prompt' => 'setPrompt',
        'required' => 'setRequired',
        'selector' => 'setSelector',
        'sort_order' => 'setSortOrder',
        'type' => 'setType',
        'updated' => 'setUpdated'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'created' => 'getCreated',
        'default_value' => 'getDefaultValue',
        'id' => 'getId',
        'label' => 'getLabel',
        'max_length' => 'getMaxLength',
        'options' => 'getOptions',
        'preference_profile' => 'getPreferenceProfile',
        'profile' => 'getProfile',
        'prompt' => 'getPrompt',
        'required' => 'getRequired',
        'selector' => 'getSelector',
        'sort_order' => 'getSortOrder',
        'type' => 'getType',
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

    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_CHECKBOXES = 'checkboxes';
    const TYPE_SELECT = 'select';
    const TYPE_TEXT = 'text';
    const TYPE_TOGGLE = 'toggle';
    const TYPE_EMAIL = 'email';
    const TYPE_DATE = 'date';
    const TYPE_NUMBER = 'number';
    

    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getTypeAllowableValues()
    {
        return [
            self::TYPE_CHECKBOX,
            self::TYPE_CHECKBOXES,
            self::TYPE_SELECT,
            self::TYPE_TEXT,
            self::TYPE_TOGGLE,
            self::TYPE_EMAIL,
            self::TYPE_DATE,
            self::TYPE_NUMBER,
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
        $this->container['default_value'] = isset($data['default_value']) ? $data['default_value'] : null;
        $this->container['id'] = isset($data['id']) ? $data['id'] : null;
        $this->container['label'] = isset($data['label']) ? $data['label'] : null;
        $this->container['max_length'] = isset($data['max_length']) ? $data['max_length'] : null;
        $this->container['options'] = isset($data['options']) ? $data['options'] : null;
        $this->container['preference_profile'] = isset($data['preference_profile']) ? $data['preference_profile'] : null;
        $this->container['profile'] = isset($data['profile']) ? $data['profile'] : null;
        $this->container['prompt'] = isset($data['prompt']) ? $data['prompt'] : null;
        $this->container['required'] = isset($data['required']) ? $data['required'] : null;
        $this->container['selector'] = isset($data['selector']) ? $data['selector'] : null;
        $this->container['sort_order'] = isset($data['sort_order']) ? $data['sort_order'] : null;
        $this->container['type'] = isset($data['type']) ? $data['type'] : null;
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
        if (!is_null($this->container['default_value']) && (mb_strlen($this->container['default_value']) > 32)) {
            $invalidProperties[] = "invalid value for 'default_value', the character length must be smaller than or equal to 32.";
        }

        if (!is_null($this->container['default_value']) && (mb_strlen($this->container['default_value']) < 0)) {
            $invalidProperties[] = "invalid value for 'default_value', the character length must be bigger than or equal to 0.";
        }

        if ($this->container['label'] === null) {
            $invalidProperties[] = "'label' can't be null";
        }
        if ((mb_strlen($this->container['label']) > 1024)) {
            $invalidProperties[] = "invalid value for 'label', the character length must be smaller than or equal to 1024.";
        }

        if ((mb_strlen($this->container['label']) < 3)) {
            $invalidProperties[] = "invalid value for 'label', the character length must be bigger than or equal to 3.";
        }

        if (!is_null($this->container['prompt']) && (mb_strlen($this->container['prompt']) > 1024)) {
            $invalidProperties[] = "invalid value for 'prompt', the character length must be smaller than or equal to 1024.";
        }

        if (!is_null($this->container['prompt']) && (mb_strlen($this->container['prompt']) < 0)) {
            $invalidProperties[] = "invalid value for 'prompt', the character length must be bigger than or equal to 0.";
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

        if ($this->container['type'] === null) {
            $invalidProperties[] = "'type' can't be null";
        }
        $allowedValues = $this->getTypeAllowableValues();
        if (!is_null($this->container['type']) && !in_array($this->container['type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'type', must be one of '%s'",
                implode("', '", $allowedValues)
            );
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
     * Gets default_value
     *
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->container['default_value'];
    }

    /**
     * Sets default_value
     *
     * @param string $default_value A default value for the setting.
     *
     * @return $this
     */
    public function setDefaultValue($default_value)
    {
        if (!is_null($default_value) && (mb_strlen($default_value) > 32)) {
            throw new \InvalidArgumentException('invalid length for $default_value when calling PreferenceProfileSetting., must be smaller than or equal to 32.');
        }
        if (!is_null($default_value) && (mb_strlen($default_value) < 0)) {
            throw new \InvalidArgumentException('invalid length for $default_value when calling PreferenceProfileSetting., must be bigger than or equal to 0.');
        }

        $this->container['default_value'] = $default_value;

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
     * @param string $label The setting label, eg, 'Please answer yes or no.
     *
     * @return $this
     */
    public function setLabel($label)
    {
        if ((mb_strlen($label) > 1024)) {
            throw new \InvalidArgumentException('invalid length for $label when calling PreferenceProfileSetting., must be smaller than or equal to 1024.');
        }
        if ((mb_strlen($label) < 3)) {
            throw new \InvalidArgumentException('invalid length for $label when calling PreferenceProfileSetting., must be bigger than or equal to 3.');
        }

        $this->container['label'] = $label;

        return $this;
    }

    /**
     * Gets max_length
     *
     * @return int
     */
    public function getMaxLength()
    {
        return $this->container['max_length'];
    }

    /**
     * Sets max_length
     *
     * @param int $max_length For textual input, the maximum length of input allowed, eg, '256'.
     *
     * @return $this
     */
    public function setMaxLength($max_length)
    {
        $this->container['max_length'] = $max_length;

        return $this;
    }

    /**
     * Gets options
     *
     * @return map[string,string]
     */
    public function getOptions()
    {
        return $this->container['options'];
    }

    /**
     * Sets options
     *
     * @param map[string,string] $options options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->container['options'] = $options;

        return $this;
    }

    /**
     * Gets preference_profile
     *
     * @return \PersonalizationClient\Model\PreferenceProfile
     */
    public function getPreferenceProfile()
    {
        return $this->container['preference_profile'];
    }

    /**
     * Sets preference_profile
     *
     * @param \PersonalizationClient\Model\PreferenceProfile $preference_profile preference_profile
     *
     * @return $this
     */
    public function setPreferenceProfile($preference_profile)
    {
        $this->container['preference_profile'] = $preference_profile;

        return $this;
    }

    /**
     * Gets profile
     *
     * @return \PersonalizationClient\Model\PreferenceProfile
     */
    public function getProfile()
    {
        return $this->container['profile'];
    }

    /**
     * Sets profile
     *
     * @param \PersonalizationClient\Model\PreferenceProfile $profile profile
     *
     * @return $this
     */
    public function setProfile($profile)
    {
        $this->container['profile'] = $profile;

        return $this;
    }

    /**
     * Gets prompt
     *
     * @return string
     */
    public function getPrompt()
    {
        return $this->container['prompt'];
    }

    /**
     * Sets prompt
     *
     * @param string $prompt A hint / tool-tip for the setting.
     *
     * @return $this
     */
    public function setPrompt($prompt)
    {
        if (!is_null($prompt) && (mb_strlen($prompt) > 1024)) {
            throw new \InvalidArgumentException('invalid length for $prompt when calling PreferenceProfileSetting., must be smaller than or equal to 1024.');
        }
        if (!is_null($prompt) && (mb_strlen($prompt) < 0)) {
            throw new \InvalidArgumentException('invalid length for $prompt when calling PreferenceProfileSetting., must be bigger than or equal to 0.');
        }

        $this->container['prompt'] = $prompt;

        return $this;
    }

    /**
     * Gets required
     *
     * @return bool
     */
    public function getRequired()
    {
        return $this->container['required'];
    }

    /**
     * Sets required
     *
     * @param bool $required required
     *
     * @return $this
     */
    public function setRequired($required)
    {
        $this->container['required'] = $required;

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
     * @param string $selector The name of the setting, eg, 'does-consent.
     *
     * @return $this
     */
    public function setSelector($selector)
    {
        if ((mb_strlen($selector) > 32)) {
            throw new \InvalidArgumentException('invalid length for $selector when calling PreferenceProfileSetting., must be smaller than or equal to 32.');
        }
        if ((mb_strlen($selector) < 3)) {
            throw new \InvalidArgumentException('invalid length for $selector when calling PreferenceProfileSetting., must be bigger than or equal to 3.');
        }

        $this->container['selector'] = $selector;

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
     * Gets type
     *
     * @return string
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param string $type Type of input-control, eg, 'toggle' or 'single'
     *
     * @return $this
     */
    public function setType($type)
    {
        $allowedValues = $this->getTypeAllowableValues();
        if (!in_array($type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'type', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['type'] = $type;

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


