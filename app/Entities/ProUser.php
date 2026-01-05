<?php
class ProUser extends User
{
    private $subStart;
    private $subEnd;

    public function __construct($username, $email, $password, $bio, $role, $address, $createdAt, $lastLogin, $subStart, $subEnd) {
        parent::__construct($username, $email, $password, $bio, 'proUser', $address, $createdAt, $lastLogin=Null);
        $this->subStart = $subStart;
        $this->subEnd = $subEnd;
    }

    public function canCreatePrivateAlbum()
    {
        return true; 
    }

    


    public function getSubStart() { return $this->subStart;}
    public function getSubEnd() { return $this->subEnd;}
    public function setSubStart($subStart) { $this->subStart = $subStart;}
    public function setSubEnd($subEnd) { $this->subEnd = $subEnd;}


    public function isSubscriptionActive()
    {
        if (!$this->subEnd) return false;
        $now = new DateTime();
        $endDate = new DateTime($this->subEnd);
        return $endDate > $now;
    }

    
}
?>