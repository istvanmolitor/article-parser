<?php

namespace Molitor\ArticleParser\Parsers;

use Molitor\ArticleParser\Article\ArticleContent;
use Molitor\HtmlParser\HtmlParser;

class TelexArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return 'telex.hu';
    }

    public function isValidArticle(): bool
    {
        return $this->html->classExists('single-article__container');
    }

    public function getMainImageSrc(): ?string
    {
        // Prefer og:image from metadata as it is reliable
        $meta = $this->html->parseMetaData();
        if (isset($meta['og:image'])) {
            return $meta['og:image'];
        }
        // Fallback: look for figure img within article header
        $img = $this->html->getByTagName('article')?->getByTagName('img');
        return $img?->getAttribute('src') ?? null;
    }

    public function getMainImageAlt(): ?string
    {
        // Try og:image:alt or the first article image alt
        $meta = $this->html->parseMetaData();
        if (isset($meta['og:image:alt'])) {
            return $meta['og:image:alt'];
        }
        return $this->html->getByTagName('article')?->getByTagName('img')?->getAttribute('alt');
    }

    public function getCreatedAt(): ?string
    {
        // Telex usually has <time datetime="YYYY-MM-DDTHH:MM:SS+02:00"> or meta article:published_time
        $timeAttr = $this->html->getByTagName('time')?->getAttribute('datetime');
        if ($timeAttr) {
            // Normalize: try to parse ISO-8601 with timezone
            $dt = date_create($timeAttr);
            if ($dt) {
                $dt->setTimezone(new \DateTimeZone('UTC'));
                return $dt->format('Y-m-d H:i:s');
            }
        }
        $meta = $this->html->parseMetaData();
        if (isset($meta['article:published_time'])) {
            $dt = date_create($meta['article:published_time']);
            if ($dt) {
                $dt->setTimezone(new \DateTimeZone('UTC'));
                return $dt->format('Y-m-d H:i:s');
            }
        }
        return null;
    }

    public function getMainImageAuthor(): ?string
    {
        // Often photo credit is in figcaption; attempt to extract small credit text if present
        $caption = $this->html->getByTagName('figure')?->getByTagName('figcaption')?->getText();
        if ($caption) {
            // Very light heuristic: look for trailing credit after 'Fotó:' or 'Photo:'
            foreach (['Fotó:', 'Photo:'] as $marker) {
                $pos = mb_stripos($caption, $marker);
                if ($pos !== false) {
                    return trim(mb_substr($caption, $pos + mb_strlen($marker)));
                }
            }
        }
        return '';
    }

    public function getLead(): ?string
    {
        // Telex commonly uses a lead/standfirst paragraph near the top, often with class "lead" or within header
        $lead = $this->html->getByClass('lead')?->getText();
        if ($lead) {
            return $lead;
        }
        // fallback to meta description via parent
        return parent::getLead();
    }

    public function getAuthors(): null|string|array
    {
        // Try common author containers on Telex pages
        $by = $this->html->getByClass('author')?->getText();
        if ($by) {
            return $by;
        }
        $by = $this->html->getByClass('byline')?->getText();
        if ($by) {
            return $by;
        }
        return null;
    }

    public function getArticleContentWrapper(): ?HtmlParser
    {
        // Telex article body is typically within <article> element
        $article = $this->html->getByTagName('article');
        if ($article) {
            // often the content is inside the article; if a main content div exists, prefer it
            $main = $article->getByClass('content') ?? $article->getByClass('article-content');
            return $main ?: $article;
        }
        return null;
    }

    public function parseArticleContentElement(ArticleContent $content, HtmlParser $element): void
    {
        // Use base parsing for common tags; handle figures and embedded iframes specifically
        $tagName = $element->getFirstTagName();
        if ($tagName === 'figure') {
            $img = $element->getByTagName('img');
            if ($img) {
                $data = $img->parseImage();
                if ($data && isset($data['src'])) {
                    $image = $content->addImage($data['src']);
                    if (isset($data['alt'])) {
                        $image->setAlt($data['alt']);
                    }
                    $cap = $element->getByTagName('figcaption')?->getText();
                    if ($cap) {
                        // Try basic credit extraction as author
                        foreach (['Fotó:', 'Photo:'] as $marker) {
                            $pos = mb_stripos($cap, $marker);
                            if ($pos !== false) {
                                $image->setAuthor(trim(mb_substr($cap, $pos + mb_strlen($marker))));
                                break;
                            }
                        }
                    }
                }
            }
        } elseif ($tagName === 'iframe') {
            $attrs = $element->getAttributes();
            if (!empty($attrs['src'])) {
                $content->addIframe($attrs['src']);
            }
        }

        parent::parseArticleContentElement($content, $element);
    }
}
