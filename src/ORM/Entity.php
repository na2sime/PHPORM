<?php

namespace ORM;

interface Entity
{
    public function getId(): ?int;

    public function setId(?int $id): void;
}