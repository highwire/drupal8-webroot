<?php
/**
 * URI
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
 * URI Class Doc Comment
 *
 * @category Class
 * @package  PersonalizationClient
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class URI implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'URI';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'absolute' => 'bool',
        'authority' => 'string',
        'fragment' => 'string',
        'host' => 'string',
        'opaque' => 'bool',
        'path' => 'string',
        'port' => 'int',
        'query' => 'string',
        'raw_authority' => 'string',
        'raw_fragment' => 'string',
        'raw_path' => 'string',
        'raw_query' => 'string',
        'raw_scheme_specific_part' => 'string',
        'raw_user_info' => 'string',
        'scheme' => 'string',
        'scheme_specific_part' => 'string',
        'user_info' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'absolute' => null,
        'authority' => null,
        'fragment' => null,
        'host' => null,
        'opaque' => null,
        'path' => null,
        'port' => 'int32',
        'query' => null,
        'raw_authority' => null,
        'raw_fragment' => null,
        'raw_path' => null,
        'raw_query' => null,
        'raw_scheme_specific_part' => null,
        'raw_user_info' => null,
        'scheme' => null,
        'scheme_specific_part' => null,
        'user_info' => null
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
        'absolute' => 'absolute',
        'authority' => 'authority',
        'fragment' => 'fragment',
        'host' => 'host',
        'opaque' => 'opaque',
        'path' => 'path',
        'port' => 'port',
        'query' => 'query',
        'raw_authority' => 'rawAuthority',
        'raw_fragment' => 'rawFragment',
        'raw_path' => 'rawPath',
        'raw_query' => 'rawQuery',
        'raw_scheme_specific_part' => 'rawSchemeSpecificPart',
        'raw_user_info' => 'rawUserInfo',
        'scheme' => 'scheme',
        'scheme_specific_part' => 'schemeSpecificPart',
        'user_info' => 'userInfo'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'absolute' => 'setAbsolute',
        'authority' => 'setAuthority',
        'fragment' => 'setFragment',
        'host' => 'setHost',
        'opaque' => 'setOpaque',
        'path' => 'setPath',
        'port' => 'setPort',
        'query' => 'setQuery',
        'raw_authority' => 'setRawAuthority',
        'raw_fragment' => 'setRawFragment',
        'raw_path' => 'setRawPath',
        'raw_query' => 'setRawQuery',
        'raw_scheme_specific_part' => 'setRawSchemeSpecificPart',
        'raw_user_info' => 'setRawUserInfo',
        'scheme' => 'setScheme',
        'scheme_specific_part' => 'setSchemeSpecificPart',
        'user_info' => 'setUserInfo'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'absolute' => 'getAbsolute',
        'authority' => 'getAuthority',
        'fragment' => 'getFragment',
        'host' => 'getHost',
        'opaque' => 'getOpaque',
        'path' => 'getPath',
        'port' => 'getPort',
        'query' => 'getQuery',
        'raw_authority' => 'getRawAuthority',
        'raw_fragment' => 'getRawFragment',
        'raw_path' => 'getRawPath',
        'raw_query' => 'getRawQuery',
        'raw_scheme_specific_part' => 'getRawSchemeSpecificPart',
        'raw_user_info' => 'getRawUserInfo',
        'scheme' => 'getScheme',
        'scheme_specific_part' => 'getSchemeSpecificPart',
        'user_info' => 'getUserInfo'
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
        $this->container['absolute'] = isset($data['absolute']) ? $data['absolute'] : null;
        $this->container['authority'] = isset($data['authority']) ? $data['authority'] : null;
        $this->container['fragment'] = isset($data['fragment']) ? $data['fragment'] : null;
        $this->container['host'] = isset($data['host']) ? $data['host'] : null;
        $this->container['opaque'] = isset($data['opaque']) ? $data['opaque'] : null;
        $this->container['path'] = isset($data['path']) ? $data['path'] : null;
        $this->container['port'] = isset($data['port']) ? $data['port'] : null;
        $this->container['query'] = isset($data['query']) ? $data['query'] : null;
        $this->container['raw_authority'] = isset($data['raw_authority']) ? $data['raw_authority'] : null;
        $this->container['raw_fragment'] = isset($data['raw_fragment']) ? $data['raw_fragment'] : null;
        $this->container['raw_path'] = isset($data['raw_path']) ? $data['raw_path'] : null;
        $this->container['raw_query'] = isset($data['raw_query']) ? $data['raw_query'] : null;
        $this->container['raw_scheme_specific_part'] = isset($data['raw_scheme_specific_part']) ? $data['raw_scheme_specific_part'] : null;
        $this->container['raw_user_info'] = isset($data['raw_user_info']) ? $data['raw_user_info'] : null;
        $this->container['scheme'] = isset($data['scheme']) ? $data['scheme'] : null;
        $this->container['scheme_specific_part'] = isset($data['scheme_specific_part']) ? $data['scheme_specific_part'] : null;
        $this->container['user_info'] = isset($data['user_info']) ? $data['user_info'] : null;
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
     * Gets absolute
     *
     * @return bool
     */
    public function getAbsolute()
    {
        return $this->container['absolute'];
    }

    /**
     * Sets absolute
     *
     * @param bool $absolute absolute
     *
     * @return $this
     */
    public function setAbsolute($absolute)
    {
        $this->container['absolute'] = $absolute;

        return $this;
    }

    /**
     * Gets authority
     *
     * @return string
     */
    public function getAuthority()
    {
        return $this->container['authority'];
    }

    /**
     * Sets authority
     *
     * @param string $authority authority
     *
     * @return $this
     */
    public function setAuthority($authority)
    {
        $this->container['authority'] = $authority;

        return $this;
    }

    /**
     * Gets fragment
     *
     * @return string
     */
    public function getFragment()
    {
        return $this->container['fragment'];
    }

    /**
     * Sets fragment
     *
     * @param string $fragment fragment
     *
     * @return $this
     */
    public function setFragment($fragment)
    {
        $this->container['fragment'] = $fragment;

        return $this;
    }

    /**
     * Gets host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->container['host'];
    }

    /**
     * Sets host
     *
     * @param string $host host
     *
     * @return $this
     */
    public function setHost($host)
    {
        $this->container['host'] = $host;

        return $this;
    }

    /**
     * Gets opaque
     *
     * @return bool
     */
    public function getOpaque()
    {
        return $this->container['opaque'];
    }

    /**
     * Sets opaque
     *
     * @param bool $opaque opaque
     *
     * @return $this
     */
    public function setOpaque($opaque)
    {
        $this->container['opaque'] = $opaque;

        return $this;
    }

    /**
     * Gets path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->container['path'];
    }

    /**
     * Sets path
     *
     * @param string $path path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->container['path'] = $path;

        return $this;
    }

    /**
     * Gets port
     *
     * @return int
     */
    public function getPort()
    {
        return $this->container['port'];
    }

    /**
     * Sets port
     *
     * @param int $port port
     *
     * @return $this
     */
    public function setPort($port)
    {
        $this->container['port'] = $port;

        return $this;
    }

    /**
     * Gets query
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->container['query'];
    }

    /**
     * Sets query
     *
     * @param string $query query
     *
     * @return $this
     */
    public function setQuery($query)
    {
        $this->container['query'] = $query;

        return $this;
    }

    /**
     * Gets raw_authority
     *
     * @return string
     */
    public function getRawAuthority()
    {
        return $this->container['raw_authority'];
    }

    /**
     * Sets raw_authority
     *
     * @param string $raw_authority raw_authority
     *
     * @return $this
     */
    public function setRawAuthority($raw_authority)
    {
        $this->container['raw_authority'] = $raw_authority;

        return $this;
    }

    /**
     * Gets raw_fragment
     *
     * @return string
     */
    public function getRawFragment()
    {
        return $this->container['raw_fragment'];
    }

    /**
     * Sets raw_fragment
     *
     * @param string $raw_fragment raw_fragment
     *
     * @return $this
     */
    public function setRawFragment($raw_fragment)
    {
        $this->container['raw_fragment'] = $raw_fragment;

        return $this;
    }

    /**
     * Gets raw_path
     *
     * @return string
     */
    public function getRawPath()
    {
        return $this->container['raw_path'];
    }

    /**
     * Sets raw_path
     *
     * @param string $raw_path raw_path
     *
     * @return $this
     */
    public function setRawPath($raw_path)
    {
        $this->container['raw_path'] = $raw_path;

        return $this;
    }

    /**
     * Gets raw_query
     *
     * @return string
     */
    public function getRawQuery()
    {
        return $this->container['raw_query'];
    }

    /**
     * Sets raw_query
     *
     * @param string $raw_query raw_query
     *
     * @return $this
     */
    public function setRawQuery($raw_query)
    {
        $this->container['raw_query'] = $raw_query;

        return $this;
    }

    /**
     * Gets raw_scheme_specific_part
     *
     * @return string
     */
    public function getRawSchemeSpecificPart()
    {
        return $this->container['raw_scheme_specific_part'];
    }

    /**
     * Sets raw_scheme_specific_part
     *
     * @param string $raw_scheme_specific_part raw_scheme_specific_part
     *
     * @return $this
     */
    public function setRawSchemeSpecificPart($raw_scheme_specific_part)
    {
        $this->container['raw_scheme_specific_part'] = $raw_scheme_specific_part;

        return $this;
    }

    /**
     * Gets raw_user_info
     *
     * @return string
     */
    public function getRawUserInfo()
    {
        return $this->container['raw_user_info'];
    }

    /**
     * Sets raw_user_info
     *
     * @param string $raw_user_info raw_user_info
     *
     * @return $this
     */
    public function setRawUserInfo($raw_user_info)
    {
        $this->container['raw_user_info'] = $raw_user_info;

        return $this;
    }

    /**
     * Gets scheme
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->container['scheme'];
    }

    /**
     * Sets scheme
     *
     * @param string $scheme scheme
     *
     * @return $this
     */
    public function setScheme($scheme)
    {
        $this->container['scheme'] = $scheme;

        return $this;
    }

    /**
     * Gets scheme_specific_part
     *
     * @return string
     */
    public function getSchemeSpecificPart()
    {
        return $this->container['scheme_specific_part'];
    }

    /**
     * Sets scheme_specific_part
     *
     * @param string $scheme_specific_part scheme_specific_part
     *
     * @return $this
     */
    public function setSchemeSpecificPart($scheme_specific_part)
    {
        $this->container['scheme_specific_part'] = $scheme_specific_part;

        return $this;
    }

    /**
     * Gets user_info
     *
     * @return string
     */
    public function getUserInfo()
    {
        return $this->container['user_info'];
    }

    /**
     * Sets user_info
     *
     * @param string $user_info user_info
     *
     * @return $this
     */
    public function setUserInfo($user_info)
    {
        $this->container['user_info'] = $user_info;

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


