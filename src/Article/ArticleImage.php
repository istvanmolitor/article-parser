<?php

namespace Molitor\ArticleParser\Article;

class ArticleImage
{
    protected string $src;

    protected ?string $alt;

    protected ?string $author;

    public function __construct(string $src)
    {
        $this->src = $src;
    }

    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAlt(?string $alt): void
    {
        $this->alt = $alt;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function getSrc(): string
    {
        return $this->src;
    }

    public function setSrc(string $src): void
    {
        $this->src = $src;
    }

    public function toArray(): array
    {
        return [
            'src' => $this->src,
            'alt' => $this->alt,
            'author' => $this->author,
        ];
    }
}
