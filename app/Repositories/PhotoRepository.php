<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Entities/Photo.php';

class PhotoRepository
{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function saveWithTags($photoTitle, $tags) {

            // filtrer array des tags
            $uniqueTags = [];
            foreach ($tags as $tag) {
                if (!in_array($tag, $uniqueTags)) {
                    $uniqueTags[] = $tag;
                }
            }
            $tags = $uniqueTags;

            // nbr de tag: ok
            if (count($tags) < 1 || count($tags) > 10) {
                return false;
            }

            
            $this->pdo->beginTransaction();

            // Insert photo
            $stmt = $this->pdo->prepare(
                "INSERT INTO photos (title) VALUES (?)"
            );
            if (!$stmt->execute([$photoTitle])) {
                $this->pdo->rollBack();
                return false;
            }

            $photoId = $this->pdo->lastInsertId();

            foreach ($tags as $tag) {

                // Check tag
                $stmt = $this->pdo->prepare(
                    "SELECT id FROM tags WHERE name = ?"
                );
                $stmt->execute([$tag]);
                $tagId = $stmt->fetch();
                if (!$tagId) {
                    $stmt = $this->pdo->prepare(
                        "INSERT INTO tags (name) VALUES (?)"
                    );
                    if (!$stmt->execute([$tag])) {
                        $this->pdo->rollBack();
                        return false;
                    }
                    $tagId = $this->pdo->lastInsertId();
                }

                // add photo and tag
                $stmt = $this->pdo->prepare(
                    "INSERT INTO photo_tags (photo_id, tag_id) VALUES (?, ?)"
                );
                if (!$stmt->execute([$photoId, $tagId])) {
                    $this->pdo->rollBack();
                    return false;
                }
            }
            $this->pdo->commit();
            return true;
    } 

}