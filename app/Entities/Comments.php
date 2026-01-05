<?php

class Comment{
    private int $id;
    private string $content;
    private int $postId;
    private int $userId;
    private string $status;
    
    public function __construct($content, $postId, $userId, $status= 'draft'){
        $this->content=$content;
        $this->postId=$postId;
        $this->userId=$userId;
        $this->status=$status;
    }
    public function getContent() { return $this->content;}
    public function getPostId() { return $this->postId;}
    public function getUserId() { return $this->userId;}
    public function getStatus() { return $this->status;}
    public function setContent($content) { $this->content = $content;}
    public function setPostId($postId) { $this->postId = $postId;}
    public function setUserId($userId) { $this->userId = $userId;}
    public function setStatus($status) { $this->status = $status;}
}
?>