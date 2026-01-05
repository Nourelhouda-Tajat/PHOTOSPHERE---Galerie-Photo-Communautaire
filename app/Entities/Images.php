<?php

class images{
    private string $name;
    private int $size;
    private string $type;
    private string $dimmension;
    
    public function __construct($name, $size, $type, $dimmension){
        $this->name=$name;
        $this->size=$size;
        $this->type=$type;
        $this->dimmension=$dimmension;
    }
    public function getName() { return $this->name;}
    public function getSize() { return $this->size;}
    public function getType() { return $this->type;}
    public function getDimmension() { return $this->dimmension;}
    public function setName($name) { $this->name = $name;}
    public function setSize($size) { $this->size = $size;}
    public function setType($type) { $this->type = $type;}
    public function setDimmension($dimmension) { $this->dimmension = $dimmension;}
}
?>