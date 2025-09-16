<?php

namespace Molitor\ArticleParser\Article;

class ImageArticleContentElement extends ArticleContentElement
{
    private ArticleImage $image;

    public function __construct(ArticleImage $image)
    {
        $this->image = $image;
    }

    public function getContent(): array
    {
        return $this->image->toArray();
    }

    public function __toString(): string
    {
        return (string)$this->image->alt;
    }
}
