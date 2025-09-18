<?php

namespace Molitor\ArticleParser\Article;

class ListArticleContentElement extends ArticleContentElement
{
    protected array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getData(): array
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
