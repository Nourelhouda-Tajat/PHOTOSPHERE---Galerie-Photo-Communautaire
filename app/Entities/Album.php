<?php

class Post{
    private string $title;
    private string $imageName;
    private string $description;
    private int $views;
    private string $status;

    
    public function __construct($title, $imageName, $description, $views, $status='draft'){
        $this->title=$title;
        $this->imageName=$imageName;
        $this->description=$description;
        $this->views=$views;
        $this->status=$status;
    }
    public function getTitle() { return $this->title;}
    public function getImageName() { return $this->imageName;}
    public function getDescription() { return $this->description;}
    public function getViews() { return $this->views;}
    public function getStatus() { return $this->status;}

    public function setTitle($title) { $this->title = $title;}
    public function setImageName($imageName) { $this->imageName = $imageName;}
    public function setDescription($description) { $this->description = $description;}
    public function setViews($views) { $this->views = $views;}
    public function setStatus($status) { $this->status = $status;}

}
?>