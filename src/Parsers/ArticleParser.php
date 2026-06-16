<?php

namespace Molitor\ArticleParser\Parsers;

use DateTime;
use DateTimeZone;
use Molitor\ArticleParser\Article\ArticleContent;
use Molitor\ArticleParser\Article\ArticleImage;
use Molitor\ArticleParser\Article\ParagraphArticleContentElement;
use Molitor\HtmlParser\HtmlParser;

abstract class ArticleParser
{
    public function __construct(
        protected HtmlParser $html
    ) {}

    abstract public function getPortal(): string;

    abstract public function isValidArticle(): bool;

    abstract public function getAuthors(): null|string|array;

    public function getTitle(): string
    {
        $h1 = $this->html->getByTagName('h1')?->getText();
        if ($h1) {
            return $h1;
        }

        return $this->html->getByTagName('title')?->getText();
    }

    public function getMainImageSrc(): ?string
    {
        $metaData = $this->html->parseMetaData();
        if (isset($metaData['og:image'])) {
            return $metaData['og:image'];
        }

        return null;
    }

    abstract public function getMainImageAlt(): ?string;

    abstract public function getCreatedAt(): ?string;

    abstract public function getMainImageAuthor(): ?string;

    public function getLanguage(): ?string
    {
        return 'hu';
    }

    public function getLead(): ?string
    {
        $metaData = $this->html->parseMetaData();
        if (isset($metaData['description'])) {
            return $metaData['description'];
        }

        return null;
    }

    public function getKeywords(): array
    {
        return $this->html->parseKeywords();
    }

    abstract public function getArticleContentWrapper(): ?HtmlParser;

    public function getArticleContent(): ArticleContent
    {
        $articleContentWrapper = $this->getArticleContentWrapper();

        $content = new ArticleContent;
        if (! $articleContentWrapper) {
            return $content;
        }

        $elements = $articleContentWrapper->getChildren();
        /** @var HtmlParser $element */
        foreach ($elements as $element) {
            try {
                $this->parseArticleContentElement($content, $element);
            } catch (\InvalidArgumentException $e) {
                // Ignore content elements
            }
        }

        return $content;
    }

    public function parseArticleContentElement(ArticleContent $content, HtmlParser $element): void
    {
        $type = $element->getFirstTagName();
        if ($type === 'p') {
            $content->add(new ParagraphArticleContentElement($element->getTextWithLinks()));
        } elseif ($type == 'ul') {
            $content->addList($element->getListByTagName('li')->getTexts());
        } elseif ($type == 'blockquote') {
            $content->addQuote($element->getTextWithLinks());
        } elseif ($type == 'h1' || $type == 'h2' || $type == 'h3') {
            $content->addHeading($element->getTextWithLinks());
        } elseif ($type == 'img') {
            $content->addImage($element->getAttributes()['src']);
        } elseif ($type == 'iframe') {
            $content->addIframe($element->getAttributes()['src']);
        }
    }

    public function getMainImage(): ?ArticleImage
    {
        $src = $this->getMainImageSrc();
        if ($src) {
            $image = new ArticleImage($src);
            $image->setAlt($this->getMainImageAlt());
            $image->setAuthor($this->getMainImageAuthor());

            return $image;
        }

        return null;
    }

    public function makeTime(string $time, string $format, string $timezone = 'UTC'): ?string
    {
        if (empty($time)) {
            return null;
        }
        $dt = DateTime::createFromFormat($format, $time, new DateTimeZone($timezone));
        if (! $dt) {
            return null;
        }

        $dt->setTimezone(new DateTimeZone('UTC'));

        return $dt->format('Y-m-d H:i:s');
    }

    public function parseImage(HtmlParser $element)
    {
        if ($element->getFirstTagName() === 'img') {
            return $element->parseImage();
        }

    }
}
