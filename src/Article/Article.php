<?php

namespace Molitor\ArticleParser\Article;

class Article
{
    private string $portal;

    private string $url;

    private string $title;

    private array $authors = [];

    private ?ArticleImage $mainImage = null;

    private string $lead;

    private string $language = 'hu';

    private array $keywords = [];

    private ArticleContent $content;

    private ?string $createdAt;

    public function __construct()
    {
        $this->content = new ArticleContent;
    }

    public function toArray(): array
    {
        return [
            'portal' => $this->portal,
            'url' => $this->url,
            'title' => $this->title,
            'authors' => $this->authors,
            'mainImage' => $this->mainImage?->toArray(),
            'lead' => $this->lead,
            'keywords' => $this->keywords,
            'content' => $this->content->toArray(),
            'language' => $this->language,
            'createdAt' => $this->createdAt,
        ];
    }

    public function __toString(): string
    {
        return implode(' ', $this->title.' '.$this->lead.' '.$this->content);
    }

    public function getPortal(): string
    {
        return $this->portal;
    }

    public function setPortal(string $portal): void
    {
        $this->portal = $portal;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function addAuthor(string $author): void
    {
        if (! empty($author)) {
            $this->authors[] = $author;
        }
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getLead(): string
    {
        return $this->lead;
    }

    public function setLead(string $lead): void
    {
        $this->lead = $lead;
    }

    public function getMainImage(): ?ArticleImage
    {
        return $this->mainImage;
    }

    public function setMainImage(?ArticleImage $mainImage): void
    {
        $this->mainImage = $mainImage;
    }

    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function addKeyword(string $keyword): void
    {
        $this->keywords[] = $keyword;
    }

    public function getContent(): ArticleContent
    {
        return $this->content;
    }

    public function setContent(ArticleContent $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }
}
