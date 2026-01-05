<?php
require_once __DIR__ . '/../app/Entities/User.php';
require_once __DIR__ . '/../app/Entities/BasicUser.php';
require_once __DIR__ . '/../app/Entities/ProUser.php';
require_once __DIR__ . '/../app/Entities/Moderator.php';
require_once __DIR__ . '/../app/Entities/Admin.php';

echo "TEST D'HÉRITAGE \n\n";

$basic = new BasicUser('nour_basic', 'nour@test.com', 'password', 'Bio d\'nour', 8);
$pro = new ProUser('taj_pro', 'taj@test.com', 'password', 'Bio de taj', '2024-01-01', '2025-01-01', 9);
$mod = new Moderator('nj_junior', 'nj@test.com', 'password', 'junior', 10);
$admin = new Admin('adminnj_super', 'adminnj@test.com', 'password', 11);

$users = [$basic, $pro, $mod, $admin];

foreach ($users as $user) {
    echo "=== {$user->getUsername()} ({$user->getRole()}) ===\n";
    echo "Peut créer album privé: " . ($user->canCreatePrivateAlbum() ? 'Oui' : 'Non') . "\n";
    
    if ($user instanceof BasicUser) {
        echo "C'est un basic user \n";
    }
    if ($user instanceof ProUser) {
        echo "Abonnement actif: " . ($user->isSubscriptionActive() ? 'Oui' : 'Non') . "\n";
    }
    if ($user instanceof Moderator) {
        echo "C'est modérateur \n";
    }
    if ($user instanceof Administrator) {
        echo "C'est admin \n";
    }
    echo "\n";
}

echo " Les classes fonctionnent\n";
?>