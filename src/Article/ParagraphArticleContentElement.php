<?php

namespace Molitor\ArticleParser\Article;

class ParagraphArticleContentElement extends ArticleContentElement
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
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
