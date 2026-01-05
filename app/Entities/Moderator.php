<?php
class Moderator extends User
{
    private string $level;
    public function __construct($username, $email, $password, $bio, $role, $address, $createdAt, $lastLogin=Null,$level)
    {
        parent::__construct($username, $email, $password,$bio='', 'moderator', $address,$createdAt, $lastLogin=NULL);
        $this->level = $level;
    }
    public function getLevel() { return $this->level;}
    public function setLevel($level) { $this->level = $level;}

    public function canCreatePrivateAlbum()
    {
        return false; 
    }

}
?>