<?php

namespace Molitor\ArticleParser\Article;

class ParagraphArticleContentElement extends ArticleContentElement
{
    private string $content;

    public function __construct(string $content)
    {
        $content = trim($content);
        if (empty($content)) {
            throw new \InvalidArgumentException('Content cannot be empty');
        }
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

    public function toHtml(): string
    {
        return '<p>'.$this->content.'</p>';
    }
}
