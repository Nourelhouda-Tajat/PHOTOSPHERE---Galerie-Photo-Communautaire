<?php
class BasicUser extends User
{
    public function __construct($username, $email, $password, $bio, $id = null)
    {
        parent::__construct( $username, $email, $password, $bio, 'basicUser',$id);
    }

    public function canCreatePrivateAlbum()
    {
        return false;
    }

    public function getUploadLimit()
    {
        return 10;
    }

     public function resetCounter()
    {
        $today = new DateTime();
        
        if ($today->format('d') === '01' && $this->getUploadCount() > 0) {
            $this->setUploadCount(0);
            
            return true;
        }
        
        return false;
    }

    
}
?>