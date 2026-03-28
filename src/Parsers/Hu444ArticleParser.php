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

    public function getMainImageSrc(): ?string
    {
        $imageData = $this->html->getBiggestImage()?->parseImage();
        if (! $imageData) {
            return null;
        }

        return $imageData['src'];
    }

    public function getAuthors(): null|string|array
    {
        return '';
    }

    public function getMainImageAlt(): ?string
    {
        return null;
    }

    public function getCreatedAt(): ?string
    {
        return null;
    }

    public function getMainImageAuthor(): ?string
    {
        return null;
    }

    public function getArticleContentWrapper(): ?HtmlParser
    {
        return null;
    }
}
