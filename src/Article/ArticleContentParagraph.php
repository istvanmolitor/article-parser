<?php

namespace Molitor\ArticleScraper\Article;

class ArticleContentParagraph extends ArticleContentElement
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getType(): string
    {
        return 'paragraph';
    }

    public function getContent(): array
    {
        return [
            'content' => $this->content,
        ];
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
