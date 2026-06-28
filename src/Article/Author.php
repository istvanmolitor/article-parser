<?php

namespace Molitor\ArticleParser\Article;

class Author
{
    public function __construct(
        private string $name,
        private ?string $image = null,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'image' => $this->image,
        ];
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
