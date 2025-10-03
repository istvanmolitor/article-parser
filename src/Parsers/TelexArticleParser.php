<?php

namespace Molitor\ArticleParser\Parsers;

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
        return $this->html->getByClass('article_img')?->getByTagName('img')?->getAttribute('src') ?? null;
    }

    public function getMainImageAlt(): ?string
    {
        return $this->html->getByClass('article_img')?->getByTagName('img')?->getAttribute('alt') ?? null;
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
        return $this->html->getByClass('article-html-content')?->getFirsChild() ?? null;
    }
}
