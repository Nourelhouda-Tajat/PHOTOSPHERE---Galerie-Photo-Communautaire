<?php
class BasicUser extends User
{
    public function __construct($id = null, $username, $email, $password, $bio)
    {
        parent::__construct($id, $username, $email, $password, $bio, 'basicUser');
    }

    public function canCreatePrivateAlbum()
    {
        return false;
    }

    public function getUploadLimit()
    {
        return 10;
    }

    

    
}