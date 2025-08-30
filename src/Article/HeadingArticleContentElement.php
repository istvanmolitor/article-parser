<?php

namespace Molitor\ArticleParser\Article;

class HeadingArticleContentElement extends ArticleContentElement
{
    public function __construct(
        private int $level,
        private string $content)
    {
    }

    public function getType(): string
    {
        return 'heading';
    }

    public function getContent(): array
    {
        return [
            'level' => $this->level,
            'content' => $this->content,
        ];
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
