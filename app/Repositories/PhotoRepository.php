<?php
require_once __DIR__ . '/../Core/Database.php';  

class PhotoRepository {

    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
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

        
        $this->db->beginTransaction();

        // Insert photo
        $stmt = $this->db->prepare(
                "INSERT INTO Photo (title, img_link, user_id, state, created_at, updated_at) 
                 VALUES (?, 'uploads/default.jpg', 1, 'draft', NOW(), NOW())"
            );
        if (!$stmt->execute([$photoTitle])) {
            $this->db->rollBack();
            return false;
        }

        $photoId = $this->db->lastInsertId();

        foreach ($tags as $tag) {

            // Check tag
            $stmt = $this->db->prepare(
                "SELECT id FROM tag WHERE name = ?"
            );
            $stmt->execute([$tag]);
            $tagId = $stmt->fetchColumn();
            if (!$tagId) {
                $stmt = $this->db->prepare(
                    "INSERT INTO tag (name) VALUES (?)"
                );
                if (!$stmt->execute([$tag])) {
                    $this->db->rollBack();
                    return false;
                }
                $tagId = $this->db->lastInsertId();
            }

            // add photo and tag
            $stmt = $this->db->prepare(
                "INSERT INTO photo_tag (photo_id, tag_id) VALUES (?, ?)"
            );
            if (!$stmt->execute([$photoId, $tagId])) {
                $this->db->rollBack();
                return false;
            }
        }
        $this->db->commit();
        return true;
    }
}

