<?php

namespace Molitor\ArticleScraper\Article;

class ArticleImage
{
    public string $src;

    public string $alt;

    public string $author;

    public function toArray(): array
    {
        return [
            'src' => $this->src,
            'alt' => $this->alt,
            'author' => $this->author,
        ];
    }
}
