<?php

namespace Molitor\ArticleParser\Parsers;

use Molitor\ArticleScraper\Article\ArticleContent;
use Molitor\ArticleScraper\Article\ArticleContentParagraph;
use Molitor\HtmlParser\HtmlParser;

class IndexArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return 'index.hu';
    }

    public function isValidArticle(): bool
    {
        return true;
    }

    public function getTitle(): string
    {
        return $this->html->getElementByFirstClassName('content-title')->stripTags();
    }

    public function getMainImageSrc(): string
    {
        return $this->html->getElementByFirstClassName('cikk-cover')->getElementByFirstTagName('img')->getAttribute('src');
    }

    public function getMainImageAlt(): string
    {
        return $this->html->getElementByFirstClassName('cikk-cover')->getElementByFirstTagName('img')->getAttribute('alt');
    }

    public function getCreatedAt(): string
    {
        return '';
    }

    public function getMainImageAuthor(): string
    {
        return '';
    }

    public function getLead(): string
    {
        return $this->html->getElementByFirstClassName('lead_container')->stripTags();
    }

    public function getAuthor(): string
    {
        return '';
    }

    public function getArticleContentWrapper(): ?HtmlParser
    {
        return $this->html->getElementByFirstClassName('cikk-torzs');
    }
}
