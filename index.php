<?php

require 'vendor/autoload.php';

use ORM\EntityManager;
use App\Entity\User;
use App\Repository\UserRepository;

$pdo = new PDO('mysql:host=localhost;dbname=ormtest', 'nassime', 'admin');
$entityManager = new EntityManager($pdo);

// Utiliser le repository spécifique pour User
$userRepository = new UserRepository($pdo);

// Créer un nouvel utilisateur
$newUser = new User();
$newUser->setName('John Doe');
$newUser->setEmail('john@example.com');
$userRepository->save($newUser);

// Récupérer un utilisateur par ID
$user = $userRepository->find(1);
if ($user !== null) {
    echo $user->getName();
}

// Récupérer un utilisateur par email
$user = $userRepository->findByEmail('john@example.com');
if ($user !== null) {
    echo $user->getEmail();
}
