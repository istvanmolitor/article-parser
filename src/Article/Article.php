<?php

namespace Molitor\ArticleScraper\Article;

use Carbon\Carbon;

class Article
{
    public string $portal;

    public string $url;

    public string $slug;

    public string $title;

    public string $author;

    public ?ArticleImage $mainImage;

    public string $lead;

    public array $keywords;

    public ?ArticleContent $content = null;

    public Carbon $createdAt;

    public function toArray(): array
    {
        return [
            'portal' => $this->portal,
            'url' => $this->url,
            'slug' => $this->slug,
            'title' => $this->title,
            'author' => $this->author,
            'mainImage' => $this->mainImage?->toArray(),
            'lead' => $this->lead,
            'keywords' => $this->keywords,
            'content' => $this->content?->toArray(),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }

    public function __toString(): string
    {
        return implode(' ', $this->title . ' ' . $this->lead . ' ' . $this->content);
    }
}
