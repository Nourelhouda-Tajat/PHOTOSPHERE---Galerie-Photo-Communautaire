<?php

class Photo implements Taggable, Commentable, Likeable
{
    use TaggableTrait;
    use TimestampableTrait;

    private ?int $id_img = null;
    private string $title;
    private ?string $description;
    private string $img_link;
    private ?int $img_size;
    private ?string $dimensions;
    private string $state;
    private int $view_count;
    private ?string $published_at;
    private int $user_id;

    /** Visibilité */
    private bool $isPublic = false;

    private int $likeCount = 0;
    private int $commentCount = 0;

    public function __construct($title, $img_link, $user_id, $description = null, $img_size = null, $dimensions = null, $state = 'draft', $view_count = 0, $published_at = null) {
        $this->title = $title;
        $this->img_link = $img_link;
        $this->user_id = $user_id;
        $this->description = $description;
        $this->img_size = $img_size;
        $this->dimensions = $dimensions;
        $this->state = $state;
        $this->view_count = $view_count;
        $this->published_at = $published_at;
        $this->initializeTimestamps();
    }


    public function addComment(string $content, int $userId): int
    {
        $this->commentCount++;
        return $this->commentCount;
    }

    public function removeComment(int $commentId): bool
    {
        if ($this->commentCount > 0) {
            $this->commentCount--;
            return true;
        }
        return false;
    }

    public function getComments(): array
    {
        return [];
    }

    public function getCommentCount(): int
    {
        return $this->commentCount;
    }


    public function addLike(int $userId): bool
    {
        $this->likeCount++;
        return true;
    }

    public function removeLike(int $userId): bool
    {
        if ($this->likeCount > 0) {
            $this->likeCount--;
            return true;
        }
        return false;
    }

    public function isLikedBy(int $userId): bool
    {
        return false;
    }

    public function getLikeCount(): int
    {
        return $this->likeCount;
    }

    public function getLikedBy(): array
    {
        return [];
    }

    public function getId(): ?int { return $this->id_img; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): ?string { return $this->description; }
    public function getImgLink(): string { return $this->img_link; }
    public function getImgSize(): ?int { return $this->img_size; }
    public function getDimensions(): ?string { return $this->dimensions; }
    public function getState(): string { return $this->state; }
    public function getViewCount(): int { return $this->view_count; }
    public function getPublishedAt(): ?string { return $this->published_at; }
    public function getUserId(): int { return $this->user_id; }
    public function isPublic(): bool { return $this->isPublic; }

    public function setPublic(bool $isPublic): void
    {
        $this->isPublic = $isPublic;
    }

    protected function loadTagsFromDatabase(): void
    {

    }
}