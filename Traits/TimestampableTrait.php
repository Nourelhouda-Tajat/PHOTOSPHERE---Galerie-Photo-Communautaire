<?php

trait TimestampableTrait
{
    protected ?\DateTimeInterface $createdAt = null;

    protected ?\DateTimeInterface $updatedAt = null;

    public function initializeTimestamps(): void
    {
        $now = new \DateTime();

        if ($this->createdAt === null) {
            $this->createdAt = $now;
        }

        $this->updatedAt = $now;
    }

    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getCreatedAt(?string $format = null)
    {
        if ($this->createdAt === null) {
            return null;
        }

        return $format
            ? $this->createdAt->format($format)
            : $this->createdAt;
    }

    public function getUpdatedAt(?string $format = null)
    {
        if ($this->updatedAt === null) {
            return null;
        }

        return $format
            ? $this->updatedAt->format($format)
            : $this->updatedAt;
    }
}
