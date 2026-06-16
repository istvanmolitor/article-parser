<?php

namespace Molitor\ArticleParser\Parsers;

use DateTimeZone;
use Molitor\ArticleParser\Article\ArticleContent;
use Molitor\HtmlParser\HtmlParser;

class PortArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return 'port.hu';
    }

    public function isValidArticle(): bool
    {
        return $this->html->classExists('article-content');
    }

    public function getTitle(): string
    {
        return $this->html->getById('heading')?->getText() ?? parent::getTitle();
    }

    public function getMainImageAlt(): ?string
    {
        return null;
    }

    public function getMainImageAuthor(): ?string
    {
        return null;
    }

    public function getCreatedAt(): ?string
    {
        $meta = $this->html->parseMetaData();
        if (isset($meta['article:published_time'])) {
            $dt = date_create($meta['article:published_time']);
            if ($dt) {
                $dt->setTimezone(new DateTimeZone('UTC'));

                return $dt->format('Y-m-d H:i:s');
            }
        }

        $timeAttr = $this->html->getByTagName('time')?->getAttribute('datetime');
        if ($timeAttr) {
            $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $timeAttr, new DateTimeZone('Europe/Budapest'));
            if ($dt) {
                $dt->setTimezone(new DateTimeZone('UTC'));

                return $dt->format('Y-m-d H:i:s');
            }
        }

        return null;
    }

    public function getAuthors(): null|string|array
    {
        foreach ($this->html->getLinkedData() as $data) {
            if (isset($data['author']) && is_array($data['author'])) {
                $names = array_values(array_filter(array_column($data['author'], 'name')));
                if (! empty($names)) {
                    return count($names) === 1 ? $names[0] : $names;
                }
            }
        }

        return $this->html->getByClass('authors')?->getByTagName('a')?->getText();
    }

    public function getLead(): ?string
    {
        $lead = $this->html->getByClass('lead')?->getByTagName('p')?->getText();
        if ($lead) {
            return $lead;
        }

        return parent::getLead();
    }

    public function getArticleContentWrapper(): ?HtmlParser
    {
        return $this->html->getByClass('article-content');
    }

    public function parseArticleContentElement(ArticleContent $content, HtmlParser $element): void
    {
        $tagName = $element->getFirstTagName();

        if ($tagName === 'div' || $tagName === 'a') {
            return;
        }

        if ($tagName === 'p') {
            $iframe = $element->getByTagName('iframe');
            if ($iframe) {
                $src = $iframe->getAttribute('src');
                if ($src) {
                    if (str_starts_with($src, '//')) {
                        $src = 'https:' . $src;
                    }
                    $content->addIframe($src);

                    return;
                }
            }
        }

        parent::parseArticleContentElement($content, $element);
    }
}
