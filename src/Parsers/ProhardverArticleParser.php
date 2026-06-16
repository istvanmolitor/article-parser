<?php

namespace Molitor\ArticleParser\Parsers;

use DateTimeZone;
use Molitor\ArticleParser\Article\ArticleContent;
use Molitor\HtmlParser\HtmlParser;

class ProhardverArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return 'prohardver.hu';
    }

    public function isValidArticle(): bool
    {
        return $this->html->classExists('content-body');
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

        $timeText = $this->html->getByTagName('time')?->getText();
        if ($timeText) {
            $dt = \DateTime::createFromFormat('Y-m-d H:i', trim($timeText), new DateTimeZone('Europe/Budapest'));
            if ($dt) {
                $dt->setTimezone(new DateTimeZone('UTC'));

                return $dt->format('Y-m-d H:i:s');
            }
        }

        return null;
    }

    public function getAuthors(): null|string|array
    {
        $meta = $this->html->parseMetaData();
        if (! empty($meta['author'])) {
            return $meta['author'];
        }

        return null;
    }

    public function getLead(): ?string
    {
        $lead = $this->html->getByClass('content-lead')?->getText();
        if ($lead) {
            return $lead;
        }

        return parent::getLead();
    }

    public function getArticleContentWrapper(): ?HtmlParser
    {
        return $this->html->getByClass('content-body');
    }

    public function parseArticleContentElement(ArticleContent $content, HtmlParser $element): void
    {
        $tagName = $element->getFirstTagName();

        if ($tagName === 'div') {
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

            $img = $element->getByTagName('img');
            if ($img) {
                $src = $img->getAttribute('src');
                if ($src) {
                    if (str_starts_with($src, '//')) {
                        $src = 'https:' . $src;
                    }
                    $image = $content->addImage($src);
                    $alt = $img->getAttribute('alt');
                    if ($alt) {
                        $image->setAlt($alt);
                    }

                    return;
                }
            }
        }

        parent::parseArticleContentElement($content, $element);
    }
}
