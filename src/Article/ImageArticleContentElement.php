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

    public function setImage(ArticleImage $image): void
    {
        $this->image = $image;
    }

    public function getAlt(): ?string
    {
        return $this->image->getAlt();
    }

    public function setAlt(?string $alt): void
    {
        $this->image->setAlt($alt);
    }

    public function getAuthor(): ?string
    {
        return $this->image->getAuthor();
    }

    public function setAuthor(?string $author): void
    {
        $this->image->setAuthor($author);
    }

    public function getSrc(): string
    {
        return $this->image->getSrc();
    }
}
