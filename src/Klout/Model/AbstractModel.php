<?php

namespace Klout\Model;

abstract class AbstractModel
{

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
