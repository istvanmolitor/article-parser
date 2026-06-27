<?php

namespace Molitor\ArticleParser\Article;

class QuoteArticleContentElement extends ParagraphArticleContentElement
{
    private ?string $author;

    public function __construct(string $content, ?string $author = null)
    {
        parent::__construct($content);
        $this->author = $author ? trim($author) : null;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function getData(): array
    {
        return array_merge(parent::getData(), ['author' => $this->author]);
    }

    public function toHtml(): string
    {
        $cite = $this->author ? '<cite>'.$this->author.'</cite>' : '';

        return '<blockquote>'.$this->getContent().$cite.'</blockquote>';
    }
}
