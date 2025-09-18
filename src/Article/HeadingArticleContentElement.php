<?php

namespace Molitor\ArticleParser\Article;

class HeadingArticleContentElement extends ArticleContentElement
{
    public function __construct(
        private int $level,
        private string $content)
    {
    }

    public function getData(): array
    {
        return [
            'level' => $this->level,
            'content' => $this->content,
        ];
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
