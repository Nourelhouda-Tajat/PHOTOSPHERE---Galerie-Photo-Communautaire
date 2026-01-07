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
try {
    $result = $albumRepo->addPhotoToAlbum(1, 2, 3);

    if ($result) {
        echo "Photo ajoutée à album avec succès";
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}



?>