<?php
// public/test_userrepo_simple.php

echo "🧪 Test UserRepository étape par étape\n";
echo "=====================================\n\n";

// Étape 1: Tester la connexion
echo "1. Test de la connexion Database...\n";
try {
    require_once __DIR__ . '/../app/Core/Database.php';
    $db = Database::getConnection();
    echo "✅ Database::getConnection() réussi\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit;
}
echo "\n";

// Étape 2: Tester UserFactory
echo "2. Test de UserFactory...\n";
try {
    require_once __DIR__ . '/../app/services/UserFactory.php';
    echo "✅ UserFactory chargé\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit;
}
echo "\n";

// Étape 3: Tester UserRepository
echo "3. Test de UserRepository...\n";
try {
    require_once __DIR__ . '/../app/Repositories/UserRepository.php';
    $repo = new UserRepository();
    echo "✅ UserRepository instancié\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit;
}
echo "\n";

// Étape 4: Tester findAll()
echo "4. Test de findAll()...\n";
try {
    $users = $repo->findAll();
    echo "✅ findAll() réussi: " . count($users) . " utilisateur(s)\n";
    
    foreach ($users as $user) {
        echo "   - " . $user->getUsername() . " (" . $user->getRole() . ")\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n✅ Tous les tests sont terminés!\n";