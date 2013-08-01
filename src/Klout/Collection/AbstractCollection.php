<?php

namespace Klout\Collection;

use Klout\Exception\InvalidArgumentException;

abstract class AbstractCollection implements \ArrayAccess, \IteratorAggregate, \Countable
{

    protected $data = array();

    protected $className;

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

    public function count()
    {
        return count($this->data);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    public function toArray()
    {
        $data = array();
        foreach ($this->data as $item) {
            $data[] = $item->toArray();
        }

        return $data;
    }

    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function set($key, $value)
    {
        if (!$value instanceof $this->className) {
            throw new InvalidArgumentException('The value being set is not an instance of ' . $className);
        }

        $this->data[$key] = $value;

        return $this;
    }

    public function remove($key)
    {
        unset($this->data[$key]);

        return $this;
    }

    public function getKeys()
    {
        return array_keys($this->data);
    }

    public function hasKey($key)
    {
        return array_key_exists($key, $this->data);
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

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

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

}
