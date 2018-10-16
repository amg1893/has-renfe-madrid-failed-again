<?php

namespace App\Entity;

abstract class AbstractEntity
{
    public function toArray(): array
    {
        var_dump($this);die;
    }
}