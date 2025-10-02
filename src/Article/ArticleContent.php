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

    public function addParagraph(string $content): ParagraphArticleContentElement
    {
        $paragraph = new ParagraphArticleContentElement($content);
        $this->add($paragraph);
        return $paragraph;
    }

    public function addImage(string $src): ImageArticleContentElement
    {
        $image = new ImageArticleContentElement(new ArticleImage($src));
        $this->add($image);
        return $image;
    }

    public function addList(array $items = []): ListArticleContentElement
    {
        $list = new ListArticleContentElement($items);
        $this->add($list);
        return $list;
    }

    public function addQuote(string $content): QuoteArticleContentElement
    {
        $quote = new QuoteArticleContentElement($content);
        $this->add($quote);
        return $quote;
    }

    public function addHeading(string $content): HeadingArticleContentElement
    {
        $heading = new HeadingArticleContentElement($content);
        $this->add($heading);
        return $heading;
    }

    public function addVideo(string $src): VideoArticleContentElement
    {
        $video = new VideoArticleContentElement($src);
        $this->add($video);
        return $video;
    }

    public function addIframe(string $src): IFrameArticleContentElement
    {
        $iframe = new IFrameArticleContentElement($src);
        $this->add($iframe);
        return $iframe;
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

    public function get(int $index): null|ArticleContentElement
    {
        return $this->elements[$index] ?? null;
    }

    public function getFirst(): null|ArticleContentElement
    {
        return $this->get(0);
    }

    public function getLast(): null|ArticleContentElement
    {
        return $this->get(count($this->elements) - 1);
    }
}
