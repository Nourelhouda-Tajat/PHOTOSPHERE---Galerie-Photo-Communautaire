<?php
require_once __DIR__ . '/../app/Repositories/TagRepository.php';

$tagRepo = new TagRepository();

/* Test 1 : tags populaires */
print_r($tagRepo->getPopularTags());

/* Test 2 : recherche */
print_r($tagRepo->searchTags('na'));

/* Test 3 : photos par tag */
print_r($tagRepo->getPhotosByTag('nature', 1, 5));

/* Test 4 : stats */
print_r($tagRepo->getTagStats('nature'));

/* Test 5 : fusion */
try {
    $tagRepo->mergeTags('ancien', 'nouveau');
    echo "Fusion réussie";
} catch (Exception $e) {
    echo $e->getMessage();
}
