<?php
interface RepositoryInterface
{
    public function findAll();
    public function findById($id);
    public function login($email, $password): bool;
    public function logout(): bool;
    public function addUser(array $userData);
    public function updateUser(User $user): bool;
    public function delete(int $id): bool;
}