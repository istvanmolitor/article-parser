<?php

namespace Molitor\ArticleParser\Article;

class Article
{
    private string $portal;

    private string $url;

    private string $title;

    private array $authors = [];

    private ArticleImage|null $mainImage = null;

    private string $lead;

    private array $keywords = [];

    private ArticleContent $content;

    private string|null $createdAt;

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
            'authors' => $this->authors,
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

    /**
     * @return string
     */
    public function getPortal(): string
    {
        return $this->portal;
    }

    /**
     * @param string $portal
     */
    public function setPortal(string $portal): void
    {
        $this->portal = $portal;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    public function addAuthor(string $author): void
    {
        if (!empty($author)) {
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

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getLead(): string
    {
        return $this->lead;
    }

    /**
     * @param string $lead
     */
    public function setLead(string $lead): void
    {
        $this->lead = $lead;
    }

    public function getMainImage(): ArticleImage|null
    {
        return $this->mainImage;
    }

    public function setMainImage(ArticleImage|null $mainImage): void
    {
        $this->mainImage = $mainImage;
    }

    /**
     * @return array
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function addKeyword(string $keyword): void
    {
        $this->keywords[] = $keyword;
    }

    /**
     * @return ArticleContent
     */
    public function getContent(): ArticleContent
    {
        return $this->content;
    }

    /**
     * @param ArticleContent $content
     */
    public function setContent(ArticleContent $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
