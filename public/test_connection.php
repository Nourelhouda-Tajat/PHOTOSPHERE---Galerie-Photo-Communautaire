<?php
require_once __DIR__ . '/../app/Core/Database.php';

try {
    $pdo = Database::getConnection();
    echo "Connexion réussie!";
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?>