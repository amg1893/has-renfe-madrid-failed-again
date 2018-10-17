<?php

namespace App\Entity;

abstract class AbstractEntity
{
    public function toArray(): array
    {
        $ret = [];
        $class = new \ReflectionClass($this);
        foreach ($class->getProperties() as $property) {
            $newKey = lcfirst(str_replace('_', '', ucwords($property->getName(), '_')));
            $method = 'get'.ucfirst($newKey);
            $ret[$newKey] = $this->{$method}();
        }
        return $ret;
    }
}