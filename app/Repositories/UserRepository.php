<?php
// app/Repositories/UserRepository.php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../services/UserFactory.php';

class UserRepository
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    
    public function findAll()
    {
        $stmt = $this->db->query("SELECT * FROM user ORDER BY id");
        $users = [];
        
        while ($data = $stmt->fetch()) {
            $user = UserFactory::createFromData($data);
            $users[] = $user;
        }
        
        return $users;
    }
    
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        
        return UserFactory::createFromData($data);
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
        return true;
    }
    
    public function addUser(array $userData)
    {
        // Hasher le mot de passe
        $hashedPassword = password_hash($userData['password'], PASSWORD_BCRYPT);
        
        $role = $userData['role'] ?? 'basicUser';
        
        // Préparer la requête avec les champs obligatoires
        $sql = "INSERT INTO user (username, email, password, bio, role, created_at) VALUES (:username, :email, :password, :bio, :role, NOW())";
        
        $params = [
            'username' => $userData['username'],
            'email' => $userData['email'],
            'password' => $hashedPassword,
            'bio' => $userData['bio'] ?? '',
            'role' => $role
        ];
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        // Récupérer l'ID généré automatiquement
        $userId = $this->db->lastInsertId();
        
        // Pour récupérer l'utilisateur complet, on peut le chercher par ID
        return $this->findById($userId);
    }
    
    public function updateUser($user): bool
    {
        $stmt = $this->db->prepare("
            UPDATE user SET 
            username = :username,
            email = :email,
            bio = :bio
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'bio' => $user->getBio(),
            'id' => $user->getId()
        ]);
    }
    
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM user WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}