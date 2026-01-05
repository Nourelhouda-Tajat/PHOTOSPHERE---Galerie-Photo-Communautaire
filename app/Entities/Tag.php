<?php

class Tag{
    private int $id;
    private string $name;
    private int $nbrPost;
    
    public function __construct($name, $nbrPost){
        $this->name=$name;
        $this->nbrPost=$nbrPost;
    }
    public function getId() { return $this->id;}
    public function getName() { return $this->name;}
    public function getNbrPost() { return $this->nbrPost;}

    public function setName($name) { $this->name = $name;}
    public function setNbrPost($nbrPost) { $this->nbrPost = $nbrPost;}
}
?>