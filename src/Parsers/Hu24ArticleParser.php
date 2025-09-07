<?php

namespace Molitor\ArticleParser\Parsers;

use Molitor\HtmlParser\HtmlParser;

class Hu24ArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return '24.hu';
    }

    public function isValidArticle(): bool
    {
        return $this->html->existsByClass('u-onlyArticlePages');
    }

    public function getTitle(): string
    {
        return $this->html->getByClass('o-post__title')?->getText();
    }

    public function getMainImageSrc(): null|string
    {
        return $this->html->getByClass('o-post__featuredImage')?->getAttribute('src') ?? null;
    }

    public function getMainImageAlt(): string
    {
        return $this->html->getByClass('o-post__featuredImage')->getAttribute('alt');
    }

    public function getCreatedAt(): string
    {
        return $this->html->getByClass('o-post__date')->parseTime('Y. m. d. H:i', 'Europe/Budapest');
    }

    public function getMainImageAuthor(): string
    {
        return '';
    }

    public function getLead(): string
    {
        $pTag = $this->html->getByClass('u-onlyArticlePages')->getByTagName('p');
        if($pTag) {
            return $pTag->getText();
        }
        return '';
    }

    public function getAuthor(): ?string
    {
        return $this->html->getByClass('m-author__authorWrap')->getByClass('m-author__name')->getText();
    }

    public function getArticleContentWrapper(): ?HtmlParser
    {
        return $this->html->getByClass('u-onlyArticlePages');
    }
}
