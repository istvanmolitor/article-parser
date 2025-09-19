<?php

namespace Molitor\ArticleParser\Article;

class ArticleImage
{
    protected string $src;

    protected null|string $alt;

    protected null|string $author;

    public function __construct(string $src)
    {
        $this->src = $src;
    }

    public function setAuthor(null|string $author): void
    {
        $this->author = $author;
    }

    public function getAuthor(): null|string
    {
        return $this->author;
    }

    public function setAlt(null|string $alt): void
    {
        $this->alt = $alt;
    }

    /**
     * @return string|null
     */
    public function getAlt(): null|string
    {
        return $this->alt;
    }

    /**
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * @param string $src
     */
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
