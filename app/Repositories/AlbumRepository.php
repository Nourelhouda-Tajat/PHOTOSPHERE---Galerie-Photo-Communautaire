<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/UserRepository.php';
// require_once __DIR__ . '/../Entities/User.php';


class AlbumRepository{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function createAlbum(int $userId, string $title, string $description, bool $isPrivate): int{
        try {
            $this->db->beginTransaction();
            $uniqueNom=$this->db->prepare(
                "SELECT id_album FROM album WHERE name = ? AND user_id = ?"
            );
            $uniqueNom->execute([ $title, $userId]);
            if($uniqueNom->fetch()){
                throw new Exception("Titre existant");
            }

            if($isPrivate){
                $userRepo = new UserRepository();
                $user=$userRepo->findById($userId);
                if(!$user->canCreatePrivateAlbum()){
                    throw new Exception("Vous n'avez pas le droit");
                    
                }
            }
            $stmt=$this->db->prepare(
                "INSERT INTO album(name, description, public, published_at, updated_at, user_id) VALUES ( ?, ?, ?,NOW(), NOW(), ?)"
            );
            $stmt->execute([$title, $description, !$isPrivate, $userId]);
            $this->db->commit();
            return (int) $this->db->lastInsertId();            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function addPhotoToAlbum(int $albumId, int $photoId, int $userId): bool{
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                "SELECT id_album FROM album WHERE id_album = ? AND user_id = ?"
            );
            $stmt->execute([$albumId, $userId]);

            if (!$stmt->fetch()) {
                throw new Exception("Album non autorisé");
            }

            $stmt = $this->db->prepare(
                "SELECT id_img FROM photo WHERE id_img = ? AND user_id = ?"
            );
            $stmt->execute([$photoId, $userId]);

            if (!$stmt->fetch()) {
                throw new Exception("Photo non autorisée");
            }

            $stmt = $this->db->prepare(
                "SELECT 1 FROM photo_album WHERE album_id = ? AND photo_id = ?"
            );
            $stmt->execute([$albumId, $photoId]);

            if ($stmt->fetch()) {
                throw new Exception("Photo existante");
            }

            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM photo_album WHERE album_id = ?"
            );
            $stmt->execute([$albumId]);
            $count = (int) $stmt->fetchColumn();

            if ($count >= 100) {
                throw new Exception("Album plein (max 100)");
            }

            $stmt = $this->db->prepare(
                "INSERT INTO photo_album (album_id, photo_id)
                VALUES (?, ?)"
            );
            $stmt->execute([$albumId, $photoId]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    public function removePhotoFromAlbum(int $albumId, int $photoId, int $userId): bool {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                "SELECT id_album FROM album WHERE id_album = ? AND user_id = ?"
            );
            $stmt->execute([$albumId, $userId]);

            if (!$stmt->fetch()) {
                throw new Exception("Album non autorisé");
            }

            $stmt = $this->db->prepare(
                "SELECT id_img FROM photo WHERE id_img = ? AND user_id = ?"
            );
            $stmt->execute([$photoId, $userId]);

            if (!$stmt->fetch()) {
                throw new Exception("Photo non autorisée");
            }

            // Supprimer le lien
            $stmt = $this->db->prepare(
                "DELETE FROM photo_album WHERE album_id = ? AND photo_id = ?"
            );
            $stmt->execute([$albumId, $photoId]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getAlbumWithPhotos(int $albumId, int $userId): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM album 
            WHERE id_album  = ?
            AND (public = 1 OR user_id = ?)"
        );
        $stmt->execute([$albumId, $userId]);
        $album = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$album) {
            return null;
        }

        $stmt = $this->db->prepare(
            "SELECT p.*
            FROM photo p
            JOIN photo_album ap ON ap.photo_id = p.id_img
            WHERE ap.album_id = ?"
        );
        $stmt->execute([$albumId]);
        $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $album['photos'] = $photos;
        return $album;
    }

    public function getUserAlbums(int $userId, bool $includePrivate = true): array
{
    $sql = "
        SELECT a.*,
               COUNT(ap.photo_id) AS photo_count
        FROM album a
        LEFT JOIN photo_album ap ON ap.album_id = a.id_album
        WHERE a.user_id = ?
    ";

    if (!$includePrivate) {
        $sql .= " AND a.public = 1";
    }

    $sql .= " GROUP BY a.id_album ORDER BY a.updated_at DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$userId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateAlbum(int $albumId, int $userId, array $data): bool
{
    $stmt = $this->db->prepare(
        "SELECT id_album FROM album WHERE id_album = ? AND user_id = ?"
    );
    $stmt->execute([$albumId, $userId]);

    if (!$stmt->fetch()) {
        throw new Exception("Album non autorisé");
    }

    $sql = "UPDATE album SET ";
    $params = [];
    $first = true;

    if (isset($data['name'])) {
        $sql .= ($first ? "" : ", ") . "name = ?";
        $params[] = $data['name'];
        $first = false;
    }

    if (isset($data['description'])) {
        $sql .= ($first ? "" : ", ") . "description = ?";
        $params[] = $data['description'];
        $first = false;
    }

    if (isset($data['isPrivate'])) {
        $sql .= ($first ? "" : ", ") . "public = ?";
        $params[] = !$data['isPrivate'];
        $first = false;
    }

    if ($first) {
        return false; 
    }

    $sql .= ", updated_at = NOW() WHERE id_album = ?";
    $params[] = $albumId;

    return $this->db->prepare($sql)->execute($params);
}

public function deleteAlbum(int $albumId, int $userId): bool
{
    $stmt = $this->db->prepare(
        "SELECT id_album FROM album WHERE id_album = ? AND user_id = ?"
    );
    $stmt->execute([$albumId, $userId]);

    if (!$stmt->fetch()) {
        throw new Exception("Album non autorisé");
    }

    error_log("Album supprimé : ID $albumId par user $userId");

    $stmt = $this->db->prepare(
        "DELETE FROM album WHERE id_album = ?"
    );

    return $stmt->execute([$albumId]);
}







}

//test
$albumRepo = new AlbumRepository();
//test1
// try {
//     $albumId = $albumRepo->createAlbum(3,' favoris', 'Description test', true);
//     echo "Album créé son ID : " . $albumId;
// } catch (Exception $e) {
//     echo "Erreur : " . $e->getMessage();
// }


//test2
// try {
//     $result = $albumRepo->addPhotoToAlbum(1, 2, 3);

//     if ($result) {
//         echo "Photo ajoutée à album avec succès";
//     }
// } catch (Exception $e) {
//     echo "Erreur : " . $e->getMessage();
// }

//test3
// try {
//     $albumRepo->removePhotoFromAlbum(1, 2, 3);
//     echo "Photo retirée de l’album";
// } catch (Exception $e) {
//     echo "Erreur : " . $e->getMessage();
// }


//test4 
// $album = $albumRepo->getAlbumWithPhotos(1, 3);

// if ($album) {
//     echo "Album : " . $album['name'];
//     echo "<pre>";
//     print_r($album['photos']);
// } else {
//     echo "Album non accessible";
// }

//test 5
// $albums = $albumRepo->getUserAlbums(3);

// foreach ($albums as $album) {
//     echo $album['name'] . " (" . $album['photo_count'] . " photos)<br>";
// }


// test6
// try {
//     $album = $albumRepo->updateAlbum(1, 3, [
//         'name' => 'Nouvel album',
//         'isPrivate' => false
//     ]);
//     echo $album ? "Album modifié" : "Aucune modification";
// } catch (Exception $e) {
//     echo $e->getMessage();
// }

//test7
// try {
//     $albumRepo->deleteAlbum(1, 3);
//     echo "Album supprimé";
// } catch (Exception $e) {
//     echo $e->getMessage();
// }






?>