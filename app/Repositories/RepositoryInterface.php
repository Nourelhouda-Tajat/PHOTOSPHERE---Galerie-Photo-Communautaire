<?php

interface RepositoryInterface
{
    public function find($id);
    
    public function findAll();
    
    public function findBy(array $criteria);
    
    public function save($entity);
    
    public function delete($id);
}
?>