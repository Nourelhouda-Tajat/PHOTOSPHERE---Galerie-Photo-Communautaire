<?php
interface RepositoryInterface
{
    public function find($id);
    
    public function login($email, $password);
    
    public function addUser(array $userData);
}