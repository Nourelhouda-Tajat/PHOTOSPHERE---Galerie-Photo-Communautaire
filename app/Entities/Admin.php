<?php
class Admin extends User
{
    public function __construct( $username, $email, $password, $id = null)
    {
        parent::__construct($username, $email, $password, '', 'admin', 'super', 0, $id);
    }

    public function canCreatePrivateAlbum()
    {
        return false;
    }

}
?>