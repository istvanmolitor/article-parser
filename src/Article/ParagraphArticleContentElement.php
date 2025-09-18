<?php

namespace Molitor\ArticleParser\Article;

class ParagraphArticleContentElement extends ArticleContentElement
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getData(): array
    {
        return [
            'content' => $this->content,
        ];
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
