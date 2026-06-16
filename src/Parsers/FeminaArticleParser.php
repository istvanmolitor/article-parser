<?php

namespace Molitor\ArticleParser\Parsers;

use DateTimeZone;
use Molitor\ArticleParser\Article\ArticleContent;
use Molitor\HtmlParser\HtmlParser;

class FeminaArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return 'femina.hu';
    }

    public function isValidArticle(): bool
    {
        return $this->html->classExists('cikk-torzs');
    }

    public function getTitle(): string
    {
        return $this->html->getByClass('cim')?->getText() ?? '';
    }

    public function getLead(): ?string
    {
        return $this->html->getByClass('lead')?->getText();
    }

    public function getAuthors(): null|string|array
    {
        return $this->html->getByClass('article-meta')?->getByTagName('b')?->getText();
    }

    public function getMainImageAlt(): ?string
    {
        return $this->html->getByClass('cikk-cover')?->getByTagName('img')?->getAttribute('alt');
    }

    public function getMainImageAuthor(): ?string
    {
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
        return $this->html->getByClass('cikk-torzs');
    }

    public function parseArticleContentElement(ArticleContent $content, HtmlParser $element): void
    {
        if ($element->getFirstTagName() === 'div' && in_array('szerkfotoimage', $element->getClasses())) {
            $img = $element->getByTagName('img');
            if ($img) {
                $imageData = $img->parseImage();
                if ($imageData && ! empty($imageData['src'])) {
                    $imageElement = $content->addImage($imageData['src']);
                    $alt = ! empty($imageData['alt']) ? $imageData['alt'] : $element->getByClass('kepala')?->getText();
                    $imageElement->setAlt($alt);
                }
            }

            return;
        }

        parent::parseArticleContentElement($content, $element);
    }
}
