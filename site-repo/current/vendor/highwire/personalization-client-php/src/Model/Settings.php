<?php
/**
 * Settings
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
 * Settings Class Doc Comment
 *
 * @category Class
 * @description The call-to-action labels, default states, and featured options for various alert types.
 * @package  PersonalizationClient
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class Settings implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'Settings';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'on_list' => '\PersonalizationClient\Model\Venue',
        'on_page' => '\PersonalizationClient\Model\Venue',
        'on_panel' => '\PersonalizationClient\Model\Venue',
        'on_tab' => '\PersonalizationClient\Model\Venue'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'on_list' => null,
        'on_page' => null,
        'on_panel' => null,
        'on_tab' => null
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
        'on_list' => 'on-list',
        'on_page' => 'on-page',
        'on_panel' => 'on-panel',
        'on_tab' => 'on-tab'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'on_list' => 'setOnList',
        'on_page' => 'setOnPage',
        'on_panel' => 'setOnPanel',
        'on_tab' => 'setOnTab'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'on_list' => 'getOnList',
        'on_page' => 'getOnPage',
        'on_panel' => 'getOnPanel',
        'on_tab' => 'getOnTab'
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
        $this->container['on_list'] = isset($data['on_list']) ? $data['on_list'] : null;
        $this->container['on_page'] = isset($data['on_page']) ? $data['on_page'] : null;
        $this->container['on_panel'] = isset($data['on_panel']) ? $data['on_panel'] : null;
        $this->container['on_tab'] = isset($data['on_tab']) ? $data['on_tab'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

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
     * Gets on_list
     *
     * @return \PersonalizationClient\Model\Venue
     */
    public function getOnList()
    {
        return $this->container['on_list'];
    }

    /**
     * Sets on_list
     *
     * @param \PersonalizationClient\Model\Venue $on_list Setting values that are used when the alert feature is on a list, eg, the My Accounts list
     *
     * @return $this
     */
    public function setOnList($on_list)
    {
        $this->container['on_list'] = $on_list;

        return $this;
    }

    /**
     * Gets on_page
     *
     * @return \PersonalizationClient\Model\Venue
     */
    public function getOnPage()
    {
        return $this->container['on_page'];
    }

    /**
     * Sets on_page
     *
     * @param \PersonalizationClient\Model\Venue $on_page Setting values that are used, eg, on the TOC page
     *
     * @return $this
     */
    public function setOnPage($on_page)
    {
        $this->container['on_page'] = $on_page;

        return $this;
    }

    /**
     * Gets on_panel
     *
     * @return \PersonalizationClient\Model\Venue
     */
    public function getOnPanel()
    {
        return $this->container['on_panel'];
    }

    /**
     * Sets on_panel
     *
     * @param \PersonalizationClient\Model\Venue $on_panel Setting values that are used on the edit panel, eg, for creating, pausing, or resuming an alert.
     *
     * @return $this
     */
    public function setOnPanel($on_panel)
    {
        $this->container['on_panel'] = $on_panel;

        return $this;
    }

    /**
     * Gets on_tab
     *
     * @return \PersonalizationClient\Model\Venue
     */
    public function getOnTab()
    {
        return $this->container['on_tab'];
    }

    /**
     * Sets on_tab
     *
     * @param \PersonalizationClient\Model\Venue $on_tab Setting values that are used when the alert feature has been selected from a list.
     *
     * @return $this
     */
    public function setOnTab($on_tab)
    {
        $this->container['on_tab'] = $on_tab;

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


