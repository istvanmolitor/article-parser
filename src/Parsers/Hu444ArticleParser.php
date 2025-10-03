<?php

namespace Molitor\ArticleParser\Parsers;

use Molitor\HtmlParser\HtmlParser;

class Hu444ArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return '444.hu';
    }

    public function isValidArticle(): bool
    {
        return $this->html->classExists('u-onlyArticlePages');
    }

    public function getTitle(): string
    {
        return $this->html->getByClass('o-post__title')?->getText();
    }

    public function getMainImageSrc(): null|string
    {
        return $this->html->getByClass('o-post__featuredImage')?->getAttribute('src') ?? null;
    }

    public function getMainImageAlt(): null|string
    {
        return $this->html->getByClass('o-post__featuredImage')?->getAttribute('alt');
    }

    public function getCreatedAt(): null|string
    {
        $time = $this->html->getByClass('o-post__date')?->getText();
        if(!$time) {
            return null;
        }
        return $this->makeTime($time . ':00', 'Y. m. d. H:i:s', 'Europe/Budapest');
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

    public function getAuthors(): null|string|array
    {
        return $this->html->getByClass('m-author__authorWrap')->getByClass('m-author__name')->getText();
    }

    public function getArticleContentWrapper(): null|HtmlParser
    {
        return $this->html->getByClass('u-onlyArticlePages');
    }
}
