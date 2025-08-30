<?php

namespace Molitor\ArticleScraper\Article;

class ArticleContent
{
    public array $elements = [];

    public function toArray(): array
    {
        $elements = [];
        foreach($this->elements as $element) {
            $elements[] = $element->toArray();
        }
        return $elements;
    }

    public function add(ArticleContentElement $element): void
    {
        $this->elements[] = $element;
    }

    public function __toString(): string
    {
        $elements = [];
        foreach($this->elements as $element) {
            $elements[] = (string)$element;
        }
        return implode(' ', $elements);
    }

    public function addParagraph(string $content): void
    {
        if(!empty($content)) {
            $this->add(new ArticleContentParagraph($content));
        }
    }

    public function addImage(ArticleImage $articleImage)
    {
        $this->add(new ArticleContentImage($articleImage));
    }

    public function addList(array $items)
    {
        $this->add(new ArticleContentList($items));
    }
}
