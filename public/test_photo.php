<?php
require_once __DIR__ . '/../app/Entities/Photo.php';

$photo = new Photo(
    "Photo test",
    "image.jpg",
    1
);

/* TAGS */
$photo->addTag('Nature');
$photo->addTag('Sunset');

echo "Tags:\n";
print_r($photo->getTags());

/* LIKES */
$photo->addLike(1);
$photo->addLike(2);

echo "Likes: " . $photo->getLikeCount() . "\n";

/* COMMENTS */
$photo->addComment("Super photo", 1);
$photo->addComment("Magnifique", 2);

echo "Comments: " . $photo->getCommentCount() . "\n";

/* TIMESTAMPS */
echo "Created at: ";
print_r($photo->getCreatedAt('Y-m-d H:i:s'));

echo "\nUpdated at: ";
print_r($photo->getUpdatedAt('Y-m-d H:i:s'));
