<?php
class Moderator extends User
{
    public function __construct( $username, $email, $password, $level,$id = null)
    {
        parent::__construct($username, $email, $password, '', 'moderator', $level, 0,$id);
    }

    public function canCreatePrivateAlbum()
    {
        return false; 
    }

}
?>