<?php

namespace Molitor\ArticleParser;

use Carbon\Carbon;
use Molitor\Scraper\Services\PageParser;

abstract class ArticleParser extends PageParser
{
    abstract public function getPortal(): string;

    abstract public function isValidArticle(): bool;

    abstract public function getAuthor(): string;

    abstract public function getTitle(): string;

    abstract public function getMainImageSrc(): string;

    abstract public function getMainImageAlt(): string;

    abstract public function getCreatedAt(): string;

    abstract public function getMainImageAuthor(): string;

    abstract public function getLead(): string;

    abstract public function getBody(): array;

    public function makeType(): ?string
    {
        if ($this->isValidArticle()) {
            return 'article';
        }
        return 'page';
    }

    public function makeData(): ?array
    {
        return [
            'portal' => $this->getPortal(),
            'url' => (string)$this->getUrl(),
            'slug' => $this->getSlug(),
            'title' => $this->getTitle(),
            'author' => $this->getAuthor(),
            'main_image' => [
                'src' => $this->getMainImageSrc(),
                'alt' => $this->getMainImageAlt(),
                'author' => $this->getMainImageAuthor(),
            ],
            'lead' => $this->getLead(),
            'content' => $this->getBody(),
            'created_at' => $this->getCreatedAt(),
        ];
    }

    public function makeExpiration(): ?Carbon
    {
        $now = new Carbon();
        return $now->addMonths(1);
    }

    public function makePriority(): int
    {
        if($this->type == 'article') {
            return 1;
        }
        return 2;
    }
}
