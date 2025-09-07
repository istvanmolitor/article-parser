<?php

namespace Molitor\ArticleParser\Article;

use ArrayIterator;

class ArticleContent
{
    public array $elements = [];

    public function toArray(): array
    {
        $elements = [];
        /** @var ArticleContentElement $element */
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

    public function addQuote(string $content): void
    {
        if(!empty($content)) {
            $this->add(new QuoteContentElement($content));
        }
    }

    public function addHeading(int $level, string $content): void
    {
        $this->add(new HeadingArticleContentElement($level, $content));
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->elements);
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function exists(int $index): bool
    {
        return isset($this->elements[$index]);
    }

    public function get(int $index): ?ArticleContentElement
    {
        return $this->elements[$index] ?? null;
    }

    public function getFirst(): ?ArticleContentElement
    {
        return $this->get(0);
    }

    public function getLast(): ?ArticleContentElement
    {
        return $this->get(count($this->elements) - 1);
    }
}
