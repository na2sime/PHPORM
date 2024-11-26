<?php

namespace App\Entity;

use ORM\Entity;
use ORM\Annotations\Table;
use ORM\Annotations\Column;
use ORM\Annotations\Id;
use ORM\Annotations\GeneratedValue;

#[Table(name: "users")]
class User implements Entity
{
    #[Id, GeneratedValue, Column(type: "integer")]
    private ?int $id = null;

    #[Column(type: "string")]
    private string $name;

    #[Column(type: "string")]
    private string $email;

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }
}
