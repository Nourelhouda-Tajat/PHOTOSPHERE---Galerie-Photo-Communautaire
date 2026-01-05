<?php
class Admin extends User
{
    public function __construct($id = null, $username, $email, $password)
    {
        parent::__construct($id, $username, $email, $password, '', 'admin', 'super', 0);
    }

    public function canCreatePrivateAlbum()
    {
        return false;
    }

}