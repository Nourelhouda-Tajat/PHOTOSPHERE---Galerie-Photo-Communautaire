<?php

class Like{
    private int $postId;
    private int $userId;
    
    public function __construct($postId, $userId){
        $this->postId=$postId;
        $this->userId=$userId;
    }
    public function getPostId() { return $this->postId;}
    public function getUserId() { return $this->userId;}

    public function setPostId($postId) { $this->postId = $postId;}
    public function setUserId($userId) { $this->userId = $userId;}
}
?>