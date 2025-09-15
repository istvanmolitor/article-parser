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

    public function getAuthor(): null|string
    {
        return $this->html->getByClass('herald-author-name')?->getText();
    }

    public function getMainImageSrc(): null|string
    {
        return $this->html->getByClass('herald-post-thumbnail-single')?->getByTagName('img')?->getAttribute('src') ?? null;
    }

    public function getMainImageAlt(): null|string
    {
        return $this->html->getByTagName('wp-post-image')?->getAttribute('title');
    }

    public function getCreatedAt(): null|string
    {
        $time = $this->html->getByClass('updated')?->getText();
        if(!$time) {
            return null;
        }
        return $this->makeTime($time . ' 00:00:00','Y-m-d H:i:s', 'Europe/Budapest');
    }

    public function getMainImageAuthor(): string
    {
       return '';
    }

    public function getArticleContentWrapper(): null|HtmlParser
    {
        return $this->html->getByClass('entry-content');
    }
}
