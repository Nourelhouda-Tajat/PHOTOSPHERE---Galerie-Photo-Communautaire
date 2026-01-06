<?php
// app/Repositories/UserRepository.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../services/UserFactory.php';
require_once __DIR__ . '/RepositoryInterface.php';

class UserRepository implements RepositoryInterface
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    
    public function findAll()
    {
        $stmt = $this->db->query("SELECT * FROM user");
        $users = [];
        
        while ($data = $stmt->fetch()) {
            $user = UserFactory::createFromData($data);
            $user->setId($data['id']); // On doit ajouter l'ID après création
            $users[] = $user;
        }
        
        return $users;
    }
    
    public function findById($id): User
    {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        
        $user = UserFactory::createFromData($data);
        $user->setId($data['id']);
        return $user;
    }
    
    public function login($email, $password): bool
    {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return false;
        }
        
        if (password_verify($password, $data['password'])) {
            // Mettre à jour last_login
            $updateStmt = $this->db->prepare(
                "UPDATE user SET last_login = NOW() WHERE id = :id"
            );
            $updateStmt->execute(['id' => $data['id']]);
            return true;
        }
        
        return false;
    }
    
    public function logout(): bool
    {
        // Simple retour true pour l'instant
        return true;
    }
    
    public function addUser(array $userData): User
    {
        // Hacher le mot de passe
        $hashedPassword = password_hash($userData['password'], PASSWORD_BCRYPT);
        
        $stmt = $this->db->prepare("
            INSERT INTO user (username, email, password, bio, role, level, upload_count, sub_start, sub_end)
            VALUES (:username, :email, :password, :bio, :role, :level, :upload_count, :sub_start, :sub_end)
        ");
        
        // Valeurs par défaut
        $role = $userData['role'] ?? 'basicUser';
        $level = $userData['level'] ?? null;
        $uploadCount = $userData['upload_count'] ?? 0;
        $subStart = $userData['sub_start'] ?? null;
        $subEnd = $userData['sub_end'] ?? null;
        
        $stmt->execute([
            'username' => $userData['username'],
            'email' => $userData['email'],
            'password' => $hashedPassword,
            'bio' => $userData['bio'] ?? '',
            'role' => $role,
            'level' => $level,
            'upload_count' => $uploadCount,
            'sub_start' => $subStart,
            'sub_end' => $subEnd
        ]);
        
        // Récupérer l'ID
        $userId = $this->db->lastInsertId();
        
        // Créer l'objet User
        $userData['id'] = $userId;
        $user = UserFactory::createFromData($userData);
        $user->setId($userId);
        
        return $user;
    }
    
    public function updateUser(User $user): bool
    {
        $stmt = $this->db->prepare("
            UPDATE user SET 
            username = :username,
            email = :email,
            password = :password,
            bio = :bio,
            role = :role,
            level = :level,
            upload_count = :upload_count,
            sub_start = :sub_start,
            sub_end = :sub_end
            WHERE id = :id
        ");
        
        // Préparer les données spécifiques au rôle
        $data = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'bio' => $user->getBio(),
            'role' => $user->getRole(),
            'id' => $user->getId()
        ];
        
        // Ajouter les champs spécifiques
        $role = $user->getRole();
        if ($role == 'admin' && method_exists($user, 'getIsSuperAdmin')) {
            $data['level'] = $user->getIsSuperAdmin() ? 'Super Admin' : 'Admin';
            $data['upload_count'] = 0;
            $data['sub_start'] = null;
            $data['sub_end'] = null;
        }
        else if ($role == 'moderator' && method_exists($user, 'getLevel')) {
            $data['level'] = $user->getLevel();
            $data['upload_count'] = 0;
            $data['sub_start'] = null;
            $data['sub_end'] = null;
        }
        else if ($role == 'proUser' && method_exists($user, 'getSubStart') && method_exists($user, 'getSubEnd')) {
            $data['level'] = null;
            $data['upload_count'] = 0;
            $data['sub_start'] = $user->getSubStart()->format('Y-m-d H:i:s');
            $data['sub_end'] = $user->getSubEnd() ? $user->getSubEnd()->format('Y-m-d H:i:s') : null;
        }
        else { // basicUser
            $data['level'] = null;
            $data['upload_count'] = $user->getUploadCount();
            $data['sub_start'] = null;
            $data['sub_end'] = null;
        }
        
        return $stmt->execute($data);
    }
    
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM user WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}