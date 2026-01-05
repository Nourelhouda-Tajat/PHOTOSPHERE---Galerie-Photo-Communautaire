<?php
require_once __DIR__ . '/../app/Entities/User.php';
require_once __DIR__ . '/../app/Entities/BasicUser.php';
require_once __DIR__ . '/../app/Entities/ProUser.php';
require_once __DIR__ . '/../app/Entities/Moderator.php';
require_once __DIR__ . '/../app/Entities/Admin.php';
require_once __DIR__ . '/../app/Repositories/RepositoryInterface.php';
require_once __DIR__ . '/../app/Repositories/UserRepository.php';
require_once __DIR__ . '/../app/Services/UserFactory.php';


echo "=== TESTS COMPLETS - PHOTOSPHERE ===\n\n";

// Test 1: Factory Pattern
echo "=== Test 1: UserFactory ===\n";
$basicFromFactory = UserFactory::createUser('basicUser', [
    'username' => 'test_factory',
    'email' => 'test@factory.com',
    'password' => 'secret'
]);

echo "Utilisateur créé via Factory: " . $basicFromFactory->getUsername() . "\n";
echo "Rôle: " . $basicFromFactory->getRole() . "\n";
echo "Limite upload: " . $basicFromFactory->getUploadLimit() . "\n";

// Test 2: Rôles disponibles
echo "\n=== Test 2: Rôles disponibles ===\n";
$roles = UserFactory::getAvailableRoles();
foreach ($roles as $key => $label) {
    echo "- $key : $label\n";
}

// Test 3: Polymorphisme avancé
echo "\n=== Test 3: Polymorphisme ===\n";
$users = [
    UserFactory::createUser('basicUser', ['username' => 'user1']),
    UserFactory::createUser('proUser', ['username' => 'user2']),
    UserFactory::createUser('moderator', ['username' => 'user3', 'level' => 'junior']),
    UserFactory::createUser('admin', ['username' => 'user4'])
];

foreach ($users as $user) {
    echo "\n" . $user->getUsername() . ":\n";
    echo "  Type: " . get_class($user) . "\n";
    echo "  Album privé: " . ($user->canCreatePrivateAlbum() ? 'ok' : 'no') . "\n";
    
    // Test de substitution de Liskov
    try {
        $user->displayInfo();
        echo "  Test Liskov: ok\n";
    } catch (\Exception $e) {
        echo "  Test Liskov: no - " . $e->getMessage() . "\n";
    }
}

// Test 4: Privilèges par rôle
echo "\n=== Test 4: Vérification des privilèges ===\n";
$testCases = [
    ['basicUser', 'createPrivateAlbum', false],
    ['proUser', 'createPrivateAlbum', true]
];

foreach ($testCases as [$role, $privilege, $expected]) {
    $result = UserFactory::hasPrivilege($role, $privilege);
    $status = $result === $expected ? 'ok' : 'no';
    echo "$role -> $privilege: $result $status (attendu: $expected)\n";
}

// Test 5: Hydratation
echo "\n=== Test 5: Hydratation d'objets ===\n";
$data = [
    'id' => 999,
    'username' => 'hydrated_user',
    'email' => 'hydrated@test.com',
    'bio' => 'Utilisateur hydraté',
    'uploadCount' => 5,
    'createdAt' => date('Y-m-d H:i:s')
];

// $user = new BasicUser();
$user->hydrate($data);

echo "Utilisateur hydraté: " . $user->getUsername() . "\n";
echo "ID: " . $user->getId() . "\n";
echo "Bio: " . $user->getBio() . "\n";
echo "Uploads: " . $user->getUploadCount() . "\n";

// Test 6: Test de connexion BD (simulé)
echo "\n=== Test 6: Repository Pattern ===\n";
echo "Note: Pour tester réellement le Repository, configurez la connexion PDO\n";
echo "Structure du Repository User:\n";
echo "- find(id): trouve un utilisateur par ID\n";
echo "- findAll(): retourne tous les utilisateurs\n";
echo "- findBy(criteria): recherche par critères\n";
echo "- save(user): sauvegarde un utilisateur\n";
echo "- delete(id): supprime un utilisateur\n";




echo "\n=== Tests terminés avec succès! ===\n";