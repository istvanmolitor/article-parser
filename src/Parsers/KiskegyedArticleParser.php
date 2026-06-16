<?php

namespace Molitor\ArticleParser\Parsers;

use DateTimeZone;
use Molitor\ArticleParser\Article\ArticleContent;
use Molitor\HtmlParser\HtmlParser;

class KiskegyedArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return 'kiskegyed.hu';
    }

    public function isValidArticle(): bool
    {
        return $this->html->idExists('articleTitle');
    }

    public function getTitle(): string
    {
        return $this->html->getById('articleTitle')?->getText() ?? '';
    }

    public function getLead(): ?string
    {
        return $this->html->getById('leadText')?->getByTagName('p')?->getText();
    }

    public function getAuthors(): null|string|array
    {
        return $this->html->getByClass('authorName')?->getText();
    }

    public function getMainImageAlt(): ?string
    {
        $alt = $this->html->getById('leadImage')?->getByTagName('img')?->getAttribute('alt');
        if ($alt && str_contains($alt, ' Fotó: ')) {
            return trim(explode(' Fotó: ', $alt, 2)[0]);
        }

        return $alt;
    }

    public function getMainImageAuthor(): ?string
    {
        $alt = $this->html->getById('leadImage')?->getByTagName('img')?->getAttribute('alt');
        if ($alt && str_contains($alt, ' Fotó: ')) {
            return trim(explode(' Fotó: ', $alt, 2)[1]);
        }

        return null;
    }

    public function getCreatedAt(): ?string
    {
        foreach ($this->html->getLinkedData() as $data) {
            $graph = $data['@graph'] ?? [$data];
            foreach ($graph as $item) {
                if (isset($item['datePublished'])) {
                    $dt = date_create($item['datePublished']);
                    if ($dt) {
                        $dt->setTimezone(new DateTimeZone('UTC'));

                        return $dt->format('Y-m-d H:i:s');
                    }
                }
            }
        }

        return null;
    }

    public function getArticleContentWrapper(): ?HtmlParser
    {
        return $this->html->getByClass('nonRegwalled');
    }

    public function getArticleContent(): ArticleContent
    {
        $content = new ArticleContent;
        $wrapper = $this->getArticleContentWrapper();
        if (! $wrapper) {
            return $content;
        }

        foreach ($wrapper->getListByQuery('.//*[self::p or self::h3]') as $element) {
            $this->parseArticleContentElement($content, $element);
        }

        return $content;
    }
}
