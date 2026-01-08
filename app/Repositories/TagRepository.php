<?php
require_once __DIR__ . '/../Core/Database.php';

class TagRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getPopularTags(int $limit = 50): array
    {
        $stmt = $this->db->prepare(
            "SELECT t.name, COUNT(pt.photo_id) AS total
             FROM tag t
             JOIN photo_tag pt ON pt.tag_id = t.id
             GROUP BY t.id
             ORDER BY total DESC
             LIMIT $limit"
        );

        $stmt->execute();
        return $stmt->fetchAll();
    }

    
    public function searchTags(string $query, int $limit = 20): array
    {
        $stmt = $this->db->prepare(
            "SELECT t.name, COUNT(pt.photo_id) AS total
             FROM tag t
             LEFT JOIN photo_tag pt ON pt.tag_id = t.id
             WHERE LOWER(t.name) LIKE LOWER(?)
             GROUP BY t.id
             ORDER BY total DESC
             LIMIT $limit"
        );

        $stmt->execute([$query . '%']);
        return $stmt->fetchAll();
    }

    public function getPhotosByTag(string $tagName, int $page = 1, int $perPage = 30): array
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare(
            "SELECT p.id_img
             FROM photo p
             JOIN photo_tag pt ON pt.photo_id = p.id_img
             JOIN tag t ON t.id = pt.tag_id
             WHERE t.name = ?"
        );
        $stmt->execute([$tagName]);
        $total = count($stmt->fetchAll());

        $stmt = $this->db->prepare(
            "SELECT p.*, u.username
             FROM photo p
             JOIN user u ON u.id = p.user_id
             JOIN photo_tag pt ON pt.photo_id = p.id_img
             JOIN tag t ON t.id = pt.tag_id
             WHERE t.name = ?
             LIMIT $perPage OFFSET $offset"
        );
        $stmt->execute([$tagName]);

        return [
            'total'   => $total,
            'page'    => $page,
            'perPage' => $perPage,
            'photos'  => $stmt->fetchAll()
        ];
    }

    public function getTagStats(string $tagName): array
    {
        // total photos
        $stmt = $this->db->prepare(
            "SELECT p.id_img
             FROM photo p
             JOIN photo_tag pt ON pt.photo_id = p.id_img
             JOIN tag t ON t.id = pt.tag_id
             WHERE t.name = ?"
        );
        $stmt->execute([$tagName]);
        $totalPhotos = count($stmt->fetchAll());

        // photos par mois
        $stmt = $this->db->prepare(
            "SELECT DATE_FORMAT(p.created_at, '%Y-%m') AS mois, COUNT(*) AS total
             FROM photo p
             JOIN photo_tag pt ON pt.photo_id = p.id_img
             JOIN tag t ON t.id = pt.tag_id
             WHERE t.name = ?
             GROUP BY mois"
        );
        $stmt->execute([$tagName]);
        $photosByMonth = $stmt->fetchAll();

        // utilisateurs actifs
        $stmt = $this->db->prepare(
            "SELECT u.username, COUNT(*) AS total
             FROM photo p
             JOIN user u ON u.id = p.user_id
             JOIN photo_tag pt ON pt.photo_id = p.id_img
             JOIN tag t ON t.id = pt.tag_id
             WHERE t.name = ?
             GROUP BY u.id
             ORDER BY total DESC"
        );
        $stmt->execute([$tagName]);
        $users = $stmt->fetchAll();

        return [
            'totalPhotos' => $totalPhotos,
            'byMonth'     => $photosByMonth,
            'topUsers'    => $users
        ];
    }

    public function mergeTags(string $fromTag, string $toTag): bool
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("SELECT id FROM tag WHERE name = ?");
            $stmt->execute([$fromTag]);
            $from = $stmt->fetch();

            $stmt->execute([$toTag]);
            $to = $stmt->fetch();

            if (!$from || !$to) {
                throw new Exception("Tag introuvable");
            }

            $stmt = $this->db->prepare(
                "UPDATE photo_tag SET tag_id = ? WHERE tag_id = ?"
            );
            $stmt->execute([$to['id'], $from['id']]);

            $stmt = $this->db->prepare(
                "DELETE FROM tag WHERE id = ?"
            );
            $stmt->execute([$from['id']]);

            error_log("Fusion tag : $fromTag -> $toTag");

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
