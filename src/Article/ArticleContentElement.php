<?php

namespace Molitor\ArticleParser\Article;

abstract class ArticleContentElement
{
    abstract public function getType(): string;

    abstract public function getContent(): array;

    abstract public function __toString(): string;

    public function toArray(): array
    {
        $content = $this->getContent();
        $content['type'] = $this->getType();
        return $content;
    }
}
