<?php

namespace Molitor\ArticleParser\Article;

class QuoteElementArticleContentElement extends ParagraphArticleContentElement
{
    public function getType(): string
    {
        return 'quote';
    }
}
