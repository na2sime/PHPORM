<?php

namespace ORM;

use PDO;
use ReflectionClass;
use Exception;

class GenericRepository implements RepositoryInterface
{
    public function __construct(private PDO $pdo, private string $entityClass)
    {
        if (!is_subclass_of($entityClass, Entity::class)) {
            throw new Exception("Invalid entity class.");
        }
    }

    public function find(int $id): ?Entity
    {
        $tableName = $this->getTableName();
        $stmt = $this->pdo->prepare("SELECT * FROM $tableName WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        return $data ? $this->hydrate($data) : null;
    }

    public function save(Entity $entity): void
    {
        $tableName = $this->getTableName();
        $columns = $this->getColumns();
        $data = $this->extract($entity);

        if ($data['id']) {
            $setClause = implode(", ", array_map(fn($c) => "$c = :$c", array_keys($data)));
            $stmt = $this->pdo->prepare("UPDATE $tableName SET $setClause WHERE id = :id");
        } else {
            $columnsClause = implode(", ", array_keys($data));
            $valuesClause = implode(", ", array_map(fn($c) => ":$c", array_keys($data)));
            $stmt = $this->pdo->prepare("INSERT INTO $tableName ($columnsClause) VALUES ($valuesClause)");
        }

        $stmt->execute($data);

        if (!$data['id']) {
            $entity->setId((int)$this->pdo->lastInsertId());
        }
    }

    public function delete(Entity $entity): void
    {
        $tableName = $this->getTableName();
        $stmt = $this->pdo->prepare("DELETE FROM $tableName WHERE id = :id");
        $stmt->execute(['id' => $entity->getId()]);
    }

    private function getTableName(): string
    {
        $reflectionClass = new ReflectionClass($this->entityClass);
        $attributes = $reflectionClass->getAttributes(Table::class);
        if (count($attributes)) {
            return $attributes[0]->newInstance()->name;
        }
        throw new Exception("Table name not found for class " . $this->entityClass);
    }

    private function getColumns(): array
    {
        $reflectionClass = new ReflectionClass($this->entityClass);
        $columns = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $attributes = $property->getAttributes(Column::class);
            if (count($attributes)) {
                $columns[$property->getName()] = $attributes[0]->newInstance()->type;
            }
        }
        return $columns;
    }

    private function hydrate(array $data): Entity
    {
        $entity = new $this->entityClass();
        foreach ($data as $field => $value) {
            $setter = 'set' . ucfirst($field);
            if (method_exists($entity, $setter)) {
                $entity->$setter($value);
            }
        }
        return $entity;
    }

    private function extract(Entity $entity): array
    {
        $reflectionClass = new ReflectionClass($this->entityClass);
        $data = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $name = $property->getName();
            $getter = 'get' . ucfirst($name);
            if (method_exists($entity, $getter)) {
                $data[$name] = $entity->$getter();
            }
        }
        return $data;
    }
}
