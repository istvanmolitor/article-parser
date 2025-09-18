<?php

namespace Molitor\ArticleParser\Article;

abstract class ArticleContentElement
{
    public function getType(): string {
        return strtolower(substr(basename(str_replace("\\", "/", get_class($this))), 0, -21));
    }

    abstract public function getData(): array;

    abstract public function __toString(): string;

    public function toArray(): array
    {
        $content = $this->getData();
        $content['type'] = $this->getType();
        return $content;
    }
}
