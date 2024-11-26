<?php

namespace App\Repository;

use ORM\GenericRepository;
use ORM\Entity;
use App\Entity\User;
use PDO;

class UserRepository extends GenericRepository
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, User::class);
    }

    // Ajoutez ici des méthodes spécifiques au repository User.

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();

        return $data ? $this->hydrate($data) : null;
    }
}
