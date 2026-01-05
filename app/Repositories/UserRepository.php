<?php
class UserRepository implements RepositoryInterface
{
    private $connection;
    
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM user WHERE id = id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }
        
        return $this->createUserFromData($data);
    }
    
   
    
    
        
        $sql = "SELECT * FROM user";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        
        $users = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->createUserFromData($data);
        }
        
        return $users;
    }
    
    public function save($user)
    {
        if ($user->getId()) {
            return $this->update($user);
        } else {
            return $this->create($user);
        }
    }
    
    public function delete($id)
    {
        $stmt = $this->connection->prepare(
            "DELETE FROM user WHERE id = id"
        );
        return $stmt->execute(['id' => $id]);
    }
    
    private function createUserFromData(array $data)
    {
        switch ($data['role']) {
            case 'basicUser':
                $user = new BasicUser(
                    $data['id'],
                    $data['username'],
                    $data['email'],
                    $data['password'],
                    $data['bio']
                );
                break;
                
            case 'proUser':
                $user = new ProUser(
                    $data['id'],
                    $data['username'],
                    $data['email'],
                    $data['password'],
                    $data['bio'],
                    $data['sub_start'],
                    $data['sub_end']
                );
                break;
                
            case 'moderator':
                $user = new Moderator(
                    $data['id'],
                    $data['username'],
                    $data['email'],
                    $data['password'],
                    $data['level']
                );
                break;
                
            case 'admin':
                $user = new Admin(
                    $data['id'],
                    $data['username'],
                    $data['email'],
                    $data['password']
                );
                break;
                
            default:
                throw new \Exception("Rôle inconnu: {$data['role']}");
        }
        
        $user->setUploadCount($data['upload_count']);
        $user->setProfileImg($data['profile_img']);
        $user->setCreatedAt($data['created_at']);
        $user->setLastLogin($data['last_login']);
        
        return $user;
    }
    
    private function create($user)
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO user (username, email, password, bio, role, level, 
             upload_count, sub_start, sub_end, profile_img, created_at, last_login) 
             VALUES (:username, :email, :password, :bio, :role, :level, 
             :upload_count, :sub_start, :sub_end, :profile_img, :created_at, :last_login)"
        );
        
        $result = $stmt->execute([
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail(),
            ':password' => password_hash($user->getPassword(), PASSWORD_BCRYPT),
            ':bio' => $user->getBio(),
            ':role' => $user->getRole(),
            ':level' => $user->getLevel(),
            ':upload_count' => $user->getUploadCount(),
            ':sub_start' => $user->getSubStart(),
            ':sub_end' => $user->getSubEnd(),
            ':profile_img' => $user->getProfileImg(),
            ':created_at' => $user->getCreatedAt(),
            ':last_login' => $user->getLastLogin()
        ]);
        
        if ($result) {
            $user->setId($this->connection->lastInsertId());
        }
        
        return $result;
    }
    
    private function update($user)
    {
        $stmt = $this->connection->prepare(
            "UPDATE user SET 
             username = :username,
             email = :email,
             bio = :bio,
             role = :role,
             level = :level,
             upload_count = :upload_count,
             sub_start = :sub_start,
             sub_end = :sub_end,
             profile_img = :profile_img,
             last_login = :last_login
             WHERE id = :id"
        );
        
        return $stmt->execute([
            ':id' => $user->getId(),
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail(),
            ':bio' => $user->getBio(),
            ':role' => $user->getRole(),
            ':level' => $user->getLevel(),
            ':upload_count' => $user->getUploadCount(),
            ':sub_start' => $user->getSubStart(),
            ':sub_end' => $user->getSubEnd(),
            ':profile_img' => $user->getProfileImg(),
            ':last_login' => $user->getLastLogin()
        ]);
    }
    
    public function findByEmail($email)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM user WHERE email = :email"
        );
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }
        
        return $this->createUserFromData($data);
    }
    
    public function updateLastLogin($userId)
    {
        $stmt = $this->connection->prepare(
            "UPDATE user SET last_login = NOW() WHERE id = :id"
        );
        return $stmt->execute([':id' => $userId]);
    }
}
?>