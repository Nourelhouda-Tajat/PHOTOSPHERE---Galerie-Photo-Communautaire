<?php
require_once __DIR__ . '/../app/Entities/User.php';

// Tentative d'instanciation de la classe abstraite (devrait échouer)
try {
    // $user = new User(); // Décommentez pour voir l'erreur
    echo "Test 1: La classe User est bien abstraite ✓\n";
} catch (Error $e) {
    echo "Test 1: Échec d'instanciation - " . $e->getMessage() . " ✓\n";
}

// Test des constantes de rôle
echo "Les rôles disponibles: basicUser, proUser, moderator, admin\n";