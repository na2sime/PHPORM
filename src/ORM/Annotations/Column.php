<?php

namespace ORM\Annotations;

#[\Attribute]
class Column {
    public function __construct(public string $type) {}
}
