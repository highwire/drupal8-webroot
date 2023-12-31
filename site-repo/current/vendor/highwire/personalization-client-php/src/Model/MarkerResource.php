<?php
/**
 * MarkerResource
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
 * MarkerResource Class Doc Comment
 *
 * @category Class
 * @package  PersonalizationClient
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class MarkerResource implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'MarkerResource';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'feature' => '\PersonalizationClient\Model\Feature',
        'owner' => '\PersonalizationClient\Model\WorkspaceResource',
        'id' => 'int',
        'created' => 'string',
        'updated' => 'string',
        'label' => 'string',
        'category' => '\PersonalizationClient\Model\MarkedCategory',
        'content' => '\PersonalizationClient\Model\MarkedContent',
        'page' => '\PersonalizationClient\Model\MarkedPage',
        'notes' => 'string',
        'links' => '\PersonalizationClient\Model\Link[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'feature' => null,
        'owner' => null,
        'id' => 'int64',
        'created' => null,
        'updated' => null,
        'label' => null,
        'category' => null,
        'content' => null,
        'page' => null,
        'notes' => null,
        'links' => null
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
        'feature' => 'feature',
        'owner' => 'owner',
        'id' => 'id',
        'created' => 'created',
        'updated' => 'updated',
        'label' => 'label',
        'category' => 'category',
        'content' => 'content',
        'page' => 'page',
        'notes' => 'notes',
        'links' => 'links'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'feature' => 'setFeature',
        'owner' => 'setOwner',
        'id' => 'setId',
        'created' => 'setCreated',
        'updated' => 'setUpdated',
        'label' => 'setLabel',
        'category' => 'setCategory',
        'content' => 'setContent',
        'page' => 'setPage',
        'notes' => 'setNotes',
        'links' => 'setLinks'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'feature' => 'getFeature',
        'owner' => 'getOwner',
        'id' => 'getId',
        'created' => 'getCreated',
        'updated' => 'getUpdated',
        'label' => 'getLabel',
        'category' => 'getCategory',
        'content' => 'getContent',
        'page' => 'getPage',
        'notes' => 'getNotes',
        'links' => 'getLinks'
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
        $this->container['feature'] = isset($data['feature']) ? $data['feature'] : null;
        $this->container['owner'] = isset($data['owner']) ? $data['owner'] : null;
        $this->container['id'] = isset($data['id']) ? $data['id'] : null;
        $this->container['created'] = isset($data['created']) ? $data['created'] : null;
        $this->container['updated'] = isset($data['updated']) ? $data['updated'] : null;
        $this->container['label'] = isset($data['label']) ? $data['label'] : null;
        $this->container['category'] = isset($data['category']) ? $data['category'] : null;
        $this->container['content'] = isset($data['content']) ? $data['content'] : null;
        $this->container['page'] = isset($data['page']) ? $data['page'] : null;
        $this->container['notes'] = isset($data['notes']) ? $data['notes'] : null;
        $this->container['links'] = isset($data['links']) ? $data['links'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['id'] === null) {
            $invalidProperties[] = "'id' can't be null";
        }
        if ($this->container['created'] === null) {
            $invalidProperties[] = "'created' can't be null";
        }
        if ($this->container['updated'] === null) {
            $invalidProperties[] = "'updated' can't be null";
        }
        if ($this->container['label'] === null) {
            $invalidProperties[] = "'label' can't be null";
        }
        if ($this->container['page'] === null) {
            $invalidProperties[] = "'page' can't be null";
        }
        if (!is_null($this->container['notes']) && (mb_strlen($this->container['notes']) > 8096)) {
            $invalidProperties[] = "invalid value for 'notes', the character length must be smaller than or equal to 8096.";
        }

        if (!is_null($this->container['notes']) && (mb_strlen($this->container['notes']) < 1)) {
            $invalidProperties[] = "invalid value for 'notes', the character length must be bigger than or equal to 1.";
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
     * Gets feature
     *
     * @return \PersonalizationClient\Model\Feature
     */
    public function getFeature()
    {
        return $this->container['feature'];
    }

    /**
     * Sets feature
     *
     * @param \PersonalizationClient\Model\Feature $feature feature
     *
     * @return $this
     */
    public function setFeature($feature)
    {
        $this->container['feature'] = $feature;

        return $this;
    }

    /**
     * Gets owner
     *
     * @return \PersonalizationClient\Model\WorkspaceResource
     */
    public function getOwner()
    {
        return $this->container['owner'];
    }

    /**
     * Sets owner
     *
     * @param \PersonalizationClient\Model\WorkspaceResource $owner owner
     *
     * @return $this
     */
    public function setOwner($owner)
    {
        $this->container['owner'] = $owner;

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
     * @param int $id The numeric key assigned by the JPA repository.
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets created
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->container['created'];
    }

    /**
     * Sets created
     *
     * @param string $created The date & time the resource was created.
     *
     * @return $this
     */
    public function setCreated($created)
    {
        $this->container['created'] = $created;

        return $this;
    }

    /**
     * Gets updated
     *
     * @return string
     */
    public function getUpdated()
    {
        return $this->container['updated'];
    }

    /**
     * Sets updated
     *
     * @param string $updated The date & time the resource was updated.
     *
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->container['updated'] = $updated;

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
     * @param string $label To aid in recall when presented with a flat list of saved search.
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->container['label'] = $label;

        return $this;
    }

    /**
     * Gets category
     *
     * @return \PersonalizationClient\Model\MarkedCategory
     */
    public function getCategory()
    {
        return $this->container['category'];
    }

    /**
     * Sets category
     *
     * @param \PersonalizationClient\Model\MarkedCategory $category The marked category (scheme & term).
     *
     * @return $this
     */
    public function setCategory($category)
    {
        $this->container['category'] = $category;

        return $this;
    }

    /**
     * Gets content
     *
     * @return \PersonalizationClient\Model\MarkedContent
     */
    public function getContent()
    {
        return $this->container['content'];
    }

    /**
     * Sets content
     *
     * @param \PersonalizationClient\Model\MarkedContent $content The marked content (Atom).
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->container['content'] = $content;

        return $this;
    }

    /**
     * Gets page
     *
     * @return \PersonalizationClient\Model\MarkedPage
     */
    public function getPage()
    {
        return $this->container['page'];
    }

    /**
     * Sets page
     *
     * @param \PersonalizationClient\Model\MarkedPage $page The marked page (URL).
     *
     * @return $this
     */
    public function setPage($page)
    {
        $this->container['page'] = $page;

        return $this;
    }

    /**
     * Gets notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->container['notes'];
    }

    /**
     * Sets notes
     *
     * @param string $notes To remind readers why the search was of interest or summary of results, etc.
     *
     * @return $this
     */
    public function setNotes($notes)
    {
        if (!is_null($notes) && (mb_strlen($notes) > 8096)) {
            throw new \InvalidArgumentException('invalid length for $notes when calling MarkerResource., must be smaller than or equal to 8096.');
        }
        if (!is_null($notes) && (mb_strlen($notes) < 1)) {
            throw new \InvalidArgumentException('invalid length for $notes when calling MarkerResource., must be bigger than or equal to 1.');
        }

        $this->container['notes'] = $notes;

        return $this;
    }

    /**
     * Gets links
     *
     * @return \PersonalizationClient\Model\Link[]
     */
    public function getLinks()
    {
        return $this->container['links'];
    }

    /**
     * Sets links
     *
     * @param \PersonalizationClient\Model\Link[] $links Hyperlinks to related resources.
     *
     * @return $this
     */
    public function setLinks($links)
    {
        $this->container['links'] = $links;

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


