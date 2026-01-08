<?php
// test_photo.php
require_once __DIR__ . '/../app/Repositories/PhotoRepository.php';


// Instanciation
$repo = new PhotoRepository();

// --- Test 1 : tags valides ---
$result = $repo->saveWithTags("Photo 1", ["nature", "été", "été"]);
echo "Test 1 : " . ($result ? "Réussi" : " Échec") . "\n\n";

// --- Test 2 : 0 tag ---
$result = $repo->saveWithTags("Photo 2", []);
echo "Test 2 : " . ($result ? "Échec " : "Réussi") . "\n\n";

// --- Test 3 : trop de tags ---
$tooManyTags = array_fill(0, 11, "tag");
$result = $repo->saveWithTags("Photo 3", $tooManyTags);
echo "Test 3 : " . ($result ? " Échec" : " Réussi") . "\n";
