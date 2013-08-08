<?php
/*
 * @package    klout-sdk-php
 * @author     Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @copyright  Copyright (c) 2013 Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @license    http://opensource.org/licenses/MIT
 * @link       https://github.com/mtougeron/klout-sdk-php
 */

namespace Klout\Model;

abstract class AbstractModel
{

    /**
     * Reflect the model and recursively transform it to an array
     *
     * @return array
     */
    public function toArray()
    {
        $reflection = new \ReflectionClass($this);
        $data = array();
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
        foreach ($properties as $property) {
            $methodName = 'get' . ucfirst($property->getName());
            if (method_exists($this, $methodName) && $reflection->getMethod($methodName)->isPublic()) {
                $row = $this->$methodName();
                if (is_object($row) && method_exists($row, 'toArray')) {
                    $row = $row->toArray();
                }
                $data[$property->getName()] = $row;
            }
        }

        return $data;
    }
}
