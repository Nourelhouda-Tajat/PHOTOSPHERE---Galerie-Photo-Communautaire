<?php
abstract class User
{
    protected $id;
    protected $username;
    protected $email;
    protected $password;
    protected $bio;
    protected $role;
    protected $level;
    protected $uploadCount;
    protected $subStart;
    protected $subEnd;
    protected $profileImg;
    protected $createdAt;
    protected $lastLogin;

    public function __construct($username, $email, $password, $bio, $role,$id = null, $level = null, $uploadCount = 0, $subStart = null, $subEnd = null, $profileImg = null, $createdAt = null, $lastLogin = null) {
        $this->id = $id;
        $this->level = $level;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->bio = $bio;
        $this->role = $role;
        $this->uploadCount = $uploadCount;
        $this->subStart = $subStart;
        $this->subEnd = $subEnd;
        $this->profileImg = $profileImg;
        $this->createdAt = $createdAt;
        $this->lastLogin = $lastLogin;
    }

    abstract public function canCreatePrivateAlbum();
    abstract public function getUploadLimit();

    public function getId() { 
        return $this->id; 
    }
    public function getUsername() {
        return $this->username; 
    }
    public function getEmail() { 
        return $this->email; 
    }
    public function getRole() { 
        return $this->role; 
    }
    public function getLevel() { 
        return $this->level; 
    }
    public function getUploadCount() { 
        return $this->uploadCount; 
    }
    public function getSubStart() { 
        return $this->subStart; 
    }
    public function getSubEnd() { 
        return $this->subEnd; 
    }
    public function getCreatedAt() { 
        return $this->createdAt; 
    }
    public function getLastLogin() { 
        return $this->lastLogin; 
    }

    public function setId($id) { 
        $this->id = $id; 
    }
    public function setUsername($username) { 
        $this->username = $username; 
    }
    public function setEmail($email) { 
        $this->email = $email; 
    }
    public function setPassword($password) { 
        $this->password = $password; 
    }
    public function setRole($role) { 
        $this->role = $role; 
    }
    public function setUploadCount($uploadCount) { 
        $this->uploadCount = $uploadCount; 
    }
    public function setSubStart($subStart) { 
        $this->subStart = $subStart; 
    }
    public function setSubEnd($subEnd) { 
        $this->subEnd = $subEnd; 
    }
    public function setProfileImg($profileImg) { 
        $this->profileImg = $profileImg; 
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function displayInfo()
    {
        echo "Utilisateur: {$this->username} ({$this->role})\n";
    }
}