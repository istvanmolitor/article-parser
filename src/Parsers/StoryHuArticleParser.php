<?php

namespace Molitor\ArticleParser\Parsers;

use Molitor\HtmlParser\HtmlParser;

class StoryHuArticleParser extends ArticleParser
{

    public function getPortal(): string
    {
        return 'story.hu';
    }

    public function isValidArticle(): bool
    {
        return $this->html->existsByClass('type-post');
    }

    public function getAuthor(): ?string
    {
        return $this->html->getByClass('herald-author-name')?->getText();
    }

    public function getMainImageAlt(): string
    {
        return $this->html->getByTagName('wp-post-image')->getAttribute('title');
    }

    public function getCreatedAt(): string
    {
        return $this->html->getByClass('updated')->parseTime('Y-m-d', 'Europe/Budapest');
    }

    public function getMainImageAuthor(): string
    {
       return '';
    }

    public function getArticleContentWrapper(): ?HtmlParser
    {
        return $this->html->getByClass('entry-content');
    }
}
