<?php

namespace Molitor\ArticleScraper\Article;

class ArticleContentImage extends ArticleContentElement
{
    private ArticleImage $image;

    public function __construct(ArticleImage $image)
    {
        $this->image = $image;
    }

    public function getType(): string
    {
        return 'image';
    }

    public function getContent(): array
    {
        return $this->image->toArray();
    }

    public function __toString(): string
    {
        return $this->image->alt;
    }
}
