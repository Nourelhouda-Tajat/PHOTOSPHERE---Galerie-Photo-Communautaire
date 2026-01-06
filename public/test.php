<?php
require_once __DIR__ . '/../app/Repositories/UserRepository.php';


$repo = new UserRepository();

/* Afficher des utilisateurs existants */
echo "Utilisateurs existants :\n";
foreach ($repo->findAll() as $user) {
    echo "- {$user->getId()} | {$user->getUsername()}\n";
}
echo "\n";

/* Ajouter un utilisateur */
echo "Ajout d'un utilisateur...\n";

$userData = [
    'username' => 'user_2026',
    'email' => 'user2026@test.com',
    'password' => '123456',
    'bio' => 'Test ajout d user',
    'role' => 'basicUser'
];

$newUser = $repo->addUser($userData);

echo "Utilisateur ajouté\n";
echo "ID généré : " . $newUser->getId() . "\n\n";

/* Vérifier la recherche par ID */
$found = $repo->findById($newUser->getId());

echo "Recherche par ID : ";
echo $found ? "OK  ({$found->getUsername()})\n\n" : "Échec\n\n";

/*Nettoyage */
$repo->delete($newUser->getId());
echo "Utilisateur supprimé \n";
 