<?php

namespace Molitor\ArticleParser\Parsers;

use DateTimeZone;
use Molitor\ArticleParser\Article\ArticleContent;
use Molitor\HtmlParser\HtmlParser;

class GlamourArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return 'glamour.hu';
    }

    public function isValidArticle(): bool
    {
        return $this->html->classExists('detailSection');
    }

    public function getLead(): ?string
    {
        return $this->html->getByClass('leadText')?->getText();
    }

    public function getAuthors(): null|string|array
    {
        return $this->html->getByClass('authorContainer')?->getByClass('name')?->getText();
    }

    public function getMainImageAlt(): ?string
    {
        return $this->html->getByClass('leadImageInfo')?->getText();
    }

    public function getMainImageAuthor(): ?string
    {
        $source = $this->html->getByClass('leadImageSource')?->getText();

        return $this->parseImageSource($source);
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
        return $this->html->getByClass('detailSection');
    }

    public function getArticleContent(): ArticleContent
    {
        $content = new ArticleContent;
        $wrapper = $this->getArticleContentWrapper();
        if (! $wrapper) {
            return $content;
        }

        $imageClass = "contains(concat(' ', normalize-space(@class), ' '), ' image ')";
        $query = ".//*[
            (self::p or self::h2 or self::div[$imageClass])
            and not(ancestor::a[contains(@class, 'article_recommendation_link')])
            and not(ancestor::div[contains(@class, 'digitalCover')])
            and not(ancestor::div[@id='articleOfferFlag'])
        ]";

        foreach ($wrapper->getListByQuery($query) as $element) {
            $this->parseArticleContentElement($content, $element);
        }

        return $content;
    }

    public function parseArticleContentElement(ArticleContent $content, HtmlParser $element): void
    {
        if ($element->getFirstTagName() === 'div' && in_array('image', $element->getClasses())) {
            $img = $element->getByTagName('img');
            if ($img) {
                $imageData = $img->parseImage();
                if ($imageData && ! empty($imageData['src'])) {
                    $imageElement = $content->addImage($imageData['src']);
                    $alt = ! empty($imageData['alt']) ? $imageData['alt'] : $element->getByClass('imageCaption')?->getText();
                    $imageElement->setAlt($alt);
                    $imageElement->setAuthor($this->parseImageSource($element->getByClass('imageSource')?->getText()));
                }
            }

            return;
        }

        parent::parseArticleContentElement($content, $element);
    }

    private function parseImageSource(?string $source): ?string
    {
        if ($source && str_contains($source, 'Fotó: ')) {
            return trim(explode('Fotó: ', $source, 2)[1]);
        }

        return $source;
    }
}
