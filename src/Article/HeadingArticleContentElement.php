<?php

namespace Molitor\ArticleParser\Article;

class HeadingArticleContentElement extends ParagraphArticleContentElement
{
    public function toHtml(): string
    {
        return '<h2>'.$this->getContent().'</h2>';
    }
}
