<?php

namespace Molitor\ArticleParser\Parsers;

use DateTimeZone;
use Molitor\ArticleParser\Article\ArticleContent;
use Molitor\HtmlParser\HtmlParser;

class NepszavaArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return 'nepszava.hu';
    }

    public function isValidArticle(): bool
    {
        return $this->html->getByQuery("//*[@itemprop='headline']") !== null;
    }

    public function getTitle(): string
    {
        return $this->html->getByQuery("//*[@itemprop='headline']")?->getText() ?? '';
    }

    public function getLead(): ?string
    {
        return $this->html->getByQuery("//*[@itemprop='description']")?->getText();
    }

    public function getAuthors(): null|string|array
    {
        return $this->html->getByQuery("//*[@itemprop='author']//*[@itemprop='name']")?->getText();
    }

    public function getMainImageAlt(): ?string
    {
        return $this->html->parseMetaData()['og:image:alt'] ?? null;
    }

    public function getMainImageAuthor(): ?string
    {
        return null;
    }

    public function getCreatedAt(): ?string
    {
        foreach ($this->html->getLinkedData() as $data) {
            // Handle both plain objects and array-wrapped JSON-LD (e.g. [{...}])
            $items = $data['@graph'] ?? (isset($data[0]) ? $data : [$data]);
            foreach ($items as $item) {
                if (is_array($item) && isset($item['datePublished'])) {
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
        return $this->html->getByQuery("//*[@itemprop='articleBody']");
    }

    public function parseArticleContentElement(ArticleContent $content, HtmlParser $element): void
    {
        if ($element->getFirstTagName() === 'div') {
            $text = $element->getTextWithLinks();
            if ($text) {
                $content->addQuote($text);
            }

            return;
        }

        parent::parseArticleContentElement($content, $element);
    }
}
