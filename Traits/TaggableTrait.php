<?php
trait TaggableTrait{
    protected array $tags =[];
    public function addTag(string $tag): void{
        $tag= $this->normalizeTag($tag);
        $this->tags[] = $tag;
    }
    public function removeTag(string $tag): void {
        $tag= $this->normalizeTag($tag);
        $index= array_search($tag, $this->tags, true);
        if ($index !== false) {
            unset($this->tags[$index]);
            $this->tags = array_values($this->tags);
        }
    }
    public function getTags(): array {
        return $this->tags;

    }
    public function hasTag(string $tag): bool {
        $tag= $this->normalizeTag($tag);
        return in_array($tag,$this->tags, true);
    }
    public function clearTags(): void {
        $this->tags = [];

    }
    public function normalizeTag(string $tag) : string{
        return strtolower(trim($tag));
    }
}
