<?php

namespace Molitor\ArticleParser\Article;

class QuoteArticleContentElement extends ParagraphArticleContentElement
{
    public function toHtml(): string
    {
        return '<blockquote>'.$this->getContent().'</blockquote>';
    }
}
