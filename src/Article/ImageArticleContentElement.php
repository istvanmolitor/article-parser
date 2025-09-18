<?php

namespace Molitor\ArticleParser\Article;

class ImageArticleContentElement extends ArticleContentElement
{
    private ArticleImage $image;

    public function __construct(ArticleImage $image)
    {
        $this->image = $image;
    }

    public function getData(): array
    {
        return $this->image->toArray();
    }

    public function __toString(): string
    {
        return $this->getAlt();
    }

    public function getImage(): ArticleImage
    {
        return $this->image;
    }

    public function getAlt(): string
    {
        return $this->image->alt;
    }

    public function getAuthor(): string
    {
        return $this->image->author;
    }

    public function getSrc(): string {
        return $this->image->src;
    }
}
