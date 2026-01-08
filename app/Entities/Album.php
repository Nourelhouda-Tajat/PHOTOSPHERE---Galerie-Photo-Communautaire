<?php

class Album{
    private int $id_album;
    private string $name;
    private boolean $public;
    private string $description;
    private DateTime $publishAt;
    private DateTime $updateAt;

    
    public function __construct($name, $public, $description, $publishAt, $updateAt){
        $this->name=$name;
        $this->public=$public;
        $this->description=$description;
        $this->publishAt=new DateTime();
        $this->updateAt=Null;
    }
    public function getName() { return $this->name;}
    public function getPublic() { return $this->public;}
    public function getDescription() { return $this->description;}
    public function getpublishAt() { return $this->publishAt;}
    public function getApdateAt() { return $this->updateAt;}

    public function setName($name) { $this->name = $name;}
    public function setPublic($public) { $this->public = $public;}
    public function setDescription($description) { $this->description = $description;}
    public function setpublishAt($publishAt) { $this->publishAt = $publishAt;}
    public function setApdateAt($updateAt) { $this->updateAt = $updateAt;}

}
?>