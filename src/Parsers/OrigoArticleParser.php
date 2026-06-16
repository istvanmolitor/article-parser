<?php

namespace Molitor\ArticleParser\Parsers;

use DateTimeZone;
use Molitor\ArticleParser\Article\ArticleContent;
use Molitor\HtmlParser\HtmlParser;

class OrigoArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return 'origo.hu';
    }

    public function isValidArticle(): bool
    {
        return $this->html->classExists('article-title');
    }

    public function getTitle(): string
    {
        return $this->html->getByClass('article-title')?->getText() ?? '';
    }

    public function getLead(): ?string
    {
        return $this->html->getByClass('article-lead')?->getTextWithLinks();
    }

    public function getAuthors(): null|string|array
    {
        $name = $this->html->getByClass('article-author-name')?->getText();

        return $name ? trim($name) : null;
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

    public function getMainImageAlt(): ?string
    {
        return $this->html->getByClass('block-content')?->getByTagName('figure')?->getByTagName('img')?->getAttribute('alt');
    }

    public function getMainImageAuthor(): ?string
    {
        return $this->html->getByClass('block-content')?->getByTagName('figure')?->getByTagName('figcaption')?->getText();
    }

    public function getArticleContentWrapper(): ?HtmlParser
    {
        return $this->html->getByClass('block-content');
    }

    public function parseArticleContentElement(ArticleContent $content, HtmlParser $element): void
    {
        $type = $element->getFirstTagName();
        if ($type === 'figure') {
            $src = $element->getByTagName('img')?->getAttribute('src');
            if ($src) {
                $content->addImage($src);
            }
        } elseif ($type === 'span') {
            foreach ($element->getListByTagName('p') as $p) {
                $text = $p->getTextWithLinks();
                if ($text) {
                    $content->addQuote($text);
                }
            }
        } else {
            parent::parseArticleContentElement($content, $element);
        }
    }
}
