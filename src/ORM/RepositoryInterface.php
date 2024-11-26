<?php

namespace ORM;

interface RepositoryInterface
{
    public function find(int $id): ?Entity;
    public function save(Entity $entity): void;
    public function delete(Entity $entity): void;
}