<?php
interface Commentable{
    public function addComment(string $content, int $userId): int;
    public function removeTag(string $tag): void;
    public function removeComment(int $commentId): bool;
    public function getComments(): array;
    public function getCommentCount(): int;
}