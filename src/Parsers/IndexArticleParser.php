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
        return $this->html->getByClass('content-title')->getText();
    }

    public function getMainImageSrc(): null|string
    {
        return $this->html->getByClass('cikk-cover')?->getByTagName('img')?->getAttribute('src')??null;
    }

    public function getMainImageAlt(): string
    {
        return $this->html->getByClass('cikk-cover')?->getByTagName('img')?->getAttribute('alt');
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
        return $this->html->getByClass('lead_container')->getText();
    }

    public function getAuthor(): ?string
    {
        return null;
    }

    public function getArticleContentWrapper(): ?HtmlParser
    {
        return $this->html->getByClass('cikk-torzs');
    }
}
