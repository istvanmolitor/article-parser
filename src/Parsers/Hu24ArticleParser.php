<?php

namespace Molitor\ArticleParser\Parsers;

use Molitor\HtmlParser\HtmlParser;

class Hu24ArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return '24.hu';
    }

    public function getSlug(): string
    {
        return $this->url->getPath();
    }

    public function isValidArticle(): bool
    {
        return $this->html->classExists('u-onlyArticlePages');
    }

    public function getTitle(): string
    {
        return $this->html->getElementByFirstClassName('o-post__title')->stripTags();
    }

    public function getMainImageSrc(): string
    {
        return $this->html->getElementByFirstClassName('o-post__featuredImage')->getAttribute('src');
    }

    public function getMainImageAlt(): string
    {
        return $this->html->getElementByFirstClassName('o-post__featuredImage')->getAttribute('alt');
    }

    public function getCreatedAt(): string
    {
        return $this->html->getElementByFirstClassName('o-post__date')->getTime('Y. m. d. H:i', 'Europe/Budapest');
    }

    public function getMainImageAuthor(): string
    {
        return '';
    }

    public function getLead(): string
    {
        $pTag = $this->html->getElementByFirstClassName('u-onlyArticlePages')->getElementByFirstTagName('p');
        if($pTag) {
            return $pTag->stripTags();
        }
        return '';
    }

    public function getAuthor(): string
    {
        return $this->html->getElementByFirstClassName('m-author__authorWrap')->getElementByFirstClassName('m-author__name')->stripTags();
    }

    public function getArticleContentWrapper(): ?HtmlParser
    {
        return $this->html->getElementByFirstClassName('u-onlyArticlePages');
    }
}
