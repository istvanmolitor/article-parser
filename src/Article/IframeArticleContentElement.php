<?php

namespace Molitor\ArticleParser\Article;

class IframeArticleContentElement extends ArticleContentElement
{
    public function __construct(
        protected string $src
    )
    {
    }

    public function getData(): array
    {
        return [
            'src' => $this->src,
        ];
    }

    public function __toString(): string
    {
        return '';
    }
}
