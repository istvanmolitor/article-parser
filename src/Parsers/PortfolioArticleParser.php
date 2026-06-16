<?php

namespace Molitor\ArticleParser\Parsers;

use DateTimeZone;
use Molitor\ArticleParser\Article\ArticleContent;
use Molitor\HtmlParser\HtmlParser;

class PortfolioArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return 'portfolio.hu';
    }

    public function isValidArticle(): bool
    {
        return $this->html->classExists('pfarticle-archive');
    }

    public function getLead(): ?string
    {
        return $this->html->getByClass('pfarticle-section-lead')?->getText();
    }

    public function getAuthors(): null|string|array
    {
        $name = $this->html->getByClass('author-name')?->getText();

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
        return null;
    }

    public function getMainImageAuthor(): ?string
    {
        return null;
    }

    public function getArticleContentWrapper(): ?HtmlParser
    {
        return $this->html->getByClass('pfarticle-section-content');
    }

    public function parseArticleContentElement(ArticleContent $content, HtmlParser $element): void
    {
        $type = $element->getFirstTagName();
        if ($type === 'figure') {
            $src = $element->getByTagName('img')?->getAttribute('src');
            if ($src) {
                $content->addImage($src);
            }
        } else {
            parent::parseArticleContentElement($content, $element);
        }
    }
}
