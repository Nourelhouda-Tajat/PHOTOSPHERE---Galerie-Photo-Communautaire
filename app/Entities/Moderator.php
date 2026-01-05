<?php
class Moderator extends User
{
    public function __construct($id = null, $username, $email, $password, $level)
    {
        parent::__construct($id, $username, $email, $password, '', 'moderator', $level, 0);
    }

    public function canCreatePrivateAlbum()
    {
        return false; 
    }

}