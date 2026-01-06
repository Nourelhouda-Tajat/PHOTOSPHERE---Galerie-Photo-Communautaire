<?php
abstract class User
{
    protected int $id = 0;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $bio;
    protected string $role;
    // protected string $address;
    protected $createdAt;
    protected $lastLogin;

    public function __construct($username, $email, $password, $bio, $role, $createdAt, $lastLogin=Null) {
        
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT); 
        $this->bio = $bio;
        $this->role = $role;
        // $this->address= $address;
        $this->createdAt = $createdAt;
        $this->lastLogin = $lastLogin;
    }

    public function getId() { return $this->id;}
    public function getUsername() { return $this->username;}
    public function getEmail() { return $this->email;}
    public function getPassword() { return $this->password;}
    public function getBio() { return $this->bio;}
    public function getRole() { return $this->role;}
    public function getAddress() { return $this->address;}
    public function getCreatedAt() { return $this->createdAt;}
    public function getLastLogin() { return $this->lastLogin;}

    public function setId($id) { $this->id = $id;}
    public function setUsername($username) { $this->username = $username;}
    public function setEmail($email) {$this->email = $email;}
    public function setPassword($password) { $this->password = $password;}
    public function setBio($bio) { $this->bio = $bio;}
    public function setRole($role) { $this->role = $role;}
    public function setAddress($address) { $this->address = $address;}
    public function setCreatedAt($createdAt) { $this->createdAt = $createdAt;}
    public function setLastLogin($lastLogin) { $this->lastLogin = $lastLogin;}

    

    public function displayInfo()
    {
        echo "Utilisateur: {$this->username} ({$this->role})\n";
    }
}
?>