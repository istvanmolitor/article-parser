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
        return true;
    }

    public function getMainImageSrc(): null|string
    {
        $imageData = $this->html->getBiggestImage()?->parseImage();
        if(!$imageData) {
            return null;
        }
        return $imageData['src'];
    }

    public function getAuthors(): null|string|array
    {
        return '';
    }

    public function getMainImageAlt(): null|string
    {
        return null;
    }

    public function getCreatedAt(): null|string
    {
        return null;
    }

    public function getMainImageAuthor(): null|string
    {
        return null;
    }

    public function getArticleContentWrapper(): null|HtmlParser
    {
        return null;
    }
}
