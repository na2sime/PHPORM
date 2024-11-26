<?php

namespace ORM;

use PDO;

class EntityManager
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getRepository(string $entityClass): RepositoryInterface
    {
        return new GenericRepository($this->pdo, $entityClass);
    }
}
