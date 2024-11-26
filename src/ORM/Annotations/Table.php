<?php

namespace ORM\Annotations;

#[\Attribute]
class Table {
    public function __construct(public string $name) {}
}
