<?php

namespace Molitor\ArticleParser\Article;

class Article
{
    public string $portal;

    public string $url;

    public string $title;

    public string $author;

    public ?ArticleImage $mainImage = null;

    public string $lead;

    public array $keywords = [];

    public ArticleContent $content;

    public string $createdAt;

    public function __construct()
    {
        $this->content = new ArticleContent();
    }

    public function toArray(): array
    {
        return [
            'portal' => $this->portal,
            'url' => $this->url,
            'title' => $this->title,
            'author' => $this->author,
            'mainImage' => $this->mainImage?->toArray(),
            'lead' => $this->lead,
            'keywords' => $this->keywords,
            'content' => $this->content->toArray(),
            'createdAt' => $this->createdAt,
        ];
    }

    public function __toString(): string
    {
        return implode(' ', $this->title . ' ' . $this->lead . ' ' . $this->content);
    }
}
