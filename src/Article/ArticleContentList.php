<?php

namespace Molitor\ArticleScraper\Article;

class ArticleContentList extends ArticleContentElement
{
    protected array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getType(): string
    {
        return 'list';
    }

    public function getContent(): array
    {
        return [
            'items' => $this->items,
        ];
    }

    public function __toString(): string
    {
        return implode(' ', $this->items);
    }
}
