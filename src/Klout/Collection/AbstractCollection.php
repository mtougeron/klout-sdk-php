<?php

namespace Klout\Collection;

use Klout\Exception\InvalidArgumentException;

abstract class AbstractCollection implements \ArrayAccess, \IteratorAggregate, \Countable
{

    /**
     * The internal array of data. Should be an array of
     * objects of the type defined in $className
     *
     * @var array
     */
    protected $data = array();

    /**
     * The name of the class that this collection contains
     *
     * @var unknown_type
     */
    protected $className;

    /**
     * The constructor
     *
     * @param array $data
     * @throws InvalidArgumentException
     */
    public function __construct(array $data = null)
    {
        if (empty($this->className)) {
            throw new InvalidArgumentException('Must set a className when extending this class.');
        }

        if (!empty($data)) {
            foreach ($data as $item) {
                if (!$item instanceof $this->className) {
                    throw new InvalidArgumentException('Not all items are an instance of ' . $this->className);
                }
            }
        }

        $this->data = $data;
    }

    /**
     * (non-PHPdoc)
     * @see Countable::count()
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * (non-PHPdoc)
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Convert the items in the collection to an array
     *
     * @return array
     */
    public function toArray()
    {
        $data = array();
        foreach ($this->data as $item) {
            $data[] = $item->toArray();
        }

        return $data;
    }

    /**
     * Return the item by key
     *
     * @param String $key
     * @return \Klout\Model\AbstractModel
     */
    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * Set the item by key. If $key is null or '' then it
     * adds it without the key.
     *
     * @param String:Numeric:Null $key
     * @param \Klout\Model\AbstractModel $value
     * @throws InvalidArgumentException
     * @return \Klout\Collection\AbstractCollection
     */
    public function set($key, $value)
    {
        if (!$value instanceof $this->className) {
            throw new InvalidArgumentException('The value being set is not an instance of ' . $className);
        }

        if (is_null($key) || $key === '') {
            $this->data[] = $value;
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Add a non-keyed item to the collection
     *
     * @param \Klout\Model\AbstractModel $value
     * @return \Klout\Collection\AbstractCollection
     */
    public function add($value)
    {
        return $this->set(null, $value);
    }

    /**
     * Remove an item from the collection
     *
     * @param String $key
     * @return \Klout\Collection\AbstractCollection
     */
    public function remove($key)
    {
        unset($this->data[$key]);

        return $this;
    }

    /**
     * Get the keys for the items in the collection
     *
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->data);
    }

    /**
     * Check if the collection has a specific key
     *
     * @param String:Numeric $key
     * @return boolean
     */
    public function hasKey($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof $this->className) {
            throw new InvalidArgumentException('The value being set is not an instance of ' . $className);
        }

        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * (non-PHPdoc)
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

}
