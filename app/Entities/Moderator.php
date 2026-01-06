<?php
class Moderator extends User
{
    private string $level;
    public function __construct($username, $email, $password, $bio, $role, $createdAt, $lastLogin=Null,$level='junior')
    {
        parent::__construct($username, $email, $password,$bio='', 'moderator',$createdAt, $lastLogin=NULL);
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