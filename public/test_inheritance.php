<?php
// public/test_userfactory.php

// Inclure les fichiers nécessaires avec les bons chemins
require_once __DIR__ . '/../app/Entities/User.php';
require_once __DIR__ . '/../app/Entities/Admin.php';
require_once __DIR__ . '/../app/Entities/BasicUser.php';
require_once __DIR__ . '/../app/Entities/Moderator.php';
require_once __DIR__ . '/../app/Entities/ProUser.php';
require_once __DIR__ . '/../app/services/UserFactory.php';

echo "🧪 Test simple de UserFactory\n";
echo "=============================\n\n";

// Test 1: Création d'un Admin
echo "Test 1: Création d'un Admin\n";
echo "---------------------------\n";

$adminData = [
    'username' => 'admin1',
    'email' => 'admin@test.com',
    'password' => 'password123',
    'bio' => 'Je suis administrateur',
    'role' => 'admin',
    
    'createdAt' => '2024-01-01 10:00:00',
    'lastLogin' => '2024-01-15 14:30:00',
    'isSuperAdmin' => true
];

try {
    $admin = UserFactory::createFromData($adminData);
    echo "✅ Admin créé avec succès!\n";
    echo "   Nom: " . $admin->getUsername() . "\n";
    echo "   Email: " . $admin->getEmail() . "\n";
    echo "   Rôle: " . $admin->getRole() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 2: Création d'un BasicUser
echo "Test 2: Création d'un BasicUser\n";
echo "-------------------------------\n";

$basicData = [
    'username' => 'user1',
    'email' => 'user@test.com',
    'password' => 'password123',
    'bio' => 'Je suis un utilisateur normal',
    'role' => 'basicUser',
    'createdAt' => '2024-01-02 11:00:00',
    'lastLogin' => null,
    'uploadCount' => 5
];

try {
    $basicUser = UserFactory::createFromData($basicData);
    echo "✅ BasicUser créé avec succès!\n";
    echo "   Nom: " . $basicUser->getUsername() . "\n";
    echo "   Email: " . $basicUser->getEmail() . "\n";
    echo "   Uploads: " . $basicUser->getUploadCount() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 3: Création d'un Moderator
echo "Test 3: Création d'un Moderator\n";
echo "-------------------------------\n";

$modData = [
    'username' => 'mod1',
    'email' => 'mod@test.com',
    'password' => 'password123',
    'bio' => 'Je suis modérateur',
    'role' => 'moderator',
    'createdAt' => '2024-01-03 12:00:00',
    'lastLogin' => '2024-01-10 09:15:00',
    'level' => 'senior'
];

try {
    $moderator = UserFactory::createFromData($modData);
    echo "✅ Moderator créé avec succès!\n";
    echo "   Nom: " . $moderator->getUsername() . "\n";
    echo "   Niveau: " . $moderator->getLevel() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 4: Création d'un ProUser
echo "Test 4: Création d'un ProUser\n";
echo "-------------------------------\n";

$proData = [
    'username' => 'pro1',
    'email' => 'pro@test.com',
    'password' => 'password123',
    'bio' => 'Je suis un utilisateur pro',
    'role' => 'proUser',
    'createdAt' => '2024-01-04 13:00:00',
    'lastLogin' => '2024-01-12 16:45:00',
    'subStart' => '2024-01-01',
    'subEnd' => '2024-12-31'
];

try {
    $proUser = UserFactory::createFromData($proData);
    echo "✅ ProUser créé avec succès!\n";
    echo "   Nom: " . $proUser->getUsername() . "\n";
    // echo "   Abonnement actif: " . ($proUser->isSubscriptionActive() ? 'Oui' : 'Non') . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 5: Tester la méthode canCreatePrivateAlbum()
echo "Test 5: Tester canCreatePrivateAlbum()\n";
echo "--------------------------------------\n";

$users = [];
if (isset($admin)) $users[] = $admin;
if (isset($basicUser)) $users[] = $basicUser;
if (isset($moderator)) $users[] = $moderator;
if (isset($proUser)) $users[] = $proUser;

foreach ($users as $user) {
    $canCreate = $user->canCreatePrivateAlbum() ? 'Oui' : 'Non';
    echo $user->getUsername() . " (" . $user->getRole() . ") peut créer un album privé: " . $canCreate . "\n";
}

echo "\n";
echo "✅ Test terminé avec succès!\n";