<?php
class BasicUser extends User
{
    private int $uploadCount;
    public function __construct($username, $email, $password, $bio, $role, $createdAt, $lastLogin=Null, $uploadCount=0)
    {
        parent::__construct($username, $email, $password, $bio, 'basicUser', $createdAt, $lastLogin=Null);
        $this->uploadCount= $uploadCount;
    }
    
    public function getUploadCount() { return $this->uploadCount;}

    public function setUploadCount($uploadCount) { $this->uploadCount = $uploadCount;}

     public function resetCounter()
    {
        $today = new DateTime();
        
        if ($today->format('d') === '01' && $this->getUploadCount() > 0) {
            $this->setUploadCount(0);
            
            return true;
        }
        
        return false;
    }
    public function canCreatePrivateAlbum()
    {
        return false;
    }
    
}
?>