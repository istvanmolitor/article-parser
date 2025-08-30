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
        return $this->html->classExists('type-post');
    }

    public function getAuthor(): string
    {
        return $this->html->getElementByFirstClassName('herald-author-name')->stripTags();
    }

    public function getMainImageAlt(): string
    {
        $this->html->getElementByFirstTagName('wp-post-image')->getAttribute('title');
    }

    public function getCreatedAt(): string
    {
        return $this->html->getElementByFirstClassName('updated')->getTime('Y-m-d', 'Europe/Budapest');
    }

    public function getMainImageAuthor(): string
    {
       return '';
    }

    public function getArticleContentWrapper(): ?HtmlParser
    {
        return $this->html->getElementByFirstClassName('entry-content');
    }
}
