<?php

namespace Molitor\ArticleParser\Article;

class QuoteContentElement extends ArticleContentParagraph
{
    public function getType(): string
    {
        return 'quote';
    }
}
