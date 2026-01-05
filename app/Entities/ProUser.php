<?php
namespace App\Entities;

class ProUser extends BasicUser
{
    private $subStart;
    private $subEnd;

    public function __construct($id = null, $username, $email, $password, $bio, $subStart = null, $subEnd = null) {
        parent::__construct($id, $username, $email, $password, $bio);
        $this->setRole('proUser');
        $this->subStart = $subStart;
        $this->subEnd = $subEnd;
    }

    public function canCreatePrivateAlbum()
    {
        return true; 
    }

    public function getUploadLimit()
    {
        return null; 
    }


    public function getSubStart()
    {
        return $this->subStart;
    }

    public function getSubEnd()
    {
        return $this->subEnd;
    }

    public function setSubStart($subStart)
    {
        $this->subStart = $subStart;
    }

    public function setSubEnd($subEnd)
    {
        $this->subEnd = $subEnd;
    }

    public function isSubscriptionActive()
    {
        if (!$this->subEnd) return false;
        $now = new DateTime();
        $endDate = new DateTime($this->subEnd);
        return $endDate > $now;
    }

    
}