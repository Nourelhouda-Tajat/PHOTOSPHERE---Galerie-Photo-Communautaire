<?php
class Admin extends User
{
    private bool $isSuperAdmin;
    public function __construct($username, $email, $password, $bio, $role, $address, $createdAt, $lastLogin, $isSuperAdmin=true)
    {
        parent::__construct($username, $email, $password, $bio, 'admin', $address, $createdAt, $lastLogin=Null);
        $this->isSuperAdmin=$isSuperAdmin;
    }
    public function getIsSuperAdmin() { return $this->getIsSuperAdmin;}
    public function setIsSuperAdmin($isSuperAdmin) { $this->isSuperAdmin = $isSuperAdmin;}

    public function canCreatePrivateAlbum()
    {
        return false;
    }

}
?>