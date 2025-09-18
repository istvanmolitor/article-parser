<?php

namespace Molitor\ArticleParser\Parsers;

use Molitor\ArticleParser\Article\ArticleContent;
use Molitor\ArticleParser\Article\ParagraphArticleContentElement;
use Molitor\ArticleParser\Article\ArticleImage;
use Molitor\HtmlParser\HtmlParser;
use DateTime;
use DateTimeZone;

abstract class ArticleParser
{
    public function __construct(
        protected HtmlParser $html
    )
    {
    }

    abstract public function getPortal(): string;

    abstract public function isValidArticle(): bool;

    abstract public function getAuthor(): null|string;

    public function getTitle(): string {
        $h1 = $this->html->getByTagName('h1')?->getText();
        if($h1) {
            return $h1;
        }
        return $this->html->getByTagName('title')?->getText();
    }

    public function getMainImageSrc(): null|string {
        $metaData = $this->html->parseMetaData();
        if(isset($metaData['og:image'])) {
            return $metaData['og:image'];
        }
        return null;
    }

    abstract public function getMainImageAlt(): null|string;

    abstract public function getCreatedAt(): null|string;

    abstract public function getMainImageAuthor(): null|string;

    public function getLead(): null|string {
        $metaData = $this->html->parseMetaData();
        if(isset($metaData['description'])) {
            return $metaData['description'];
        }
        return null;
    }

    public function getKeywords(): array
    {
        return $this->html->parseKeywords();
    }

    abstract public function getArticleContentWrapper(): null|HtmlParser;

    public function getArticleContent(): ArticleContent {
        $articleContentWrapper = $this->getArticleContentWrapper();

        $content = new ArticleContent();
        if(!$articleContentWrapper) {
            return $content;
        }

        $elements = $articleContentWrapper->getChildren();
        /** @var HtmlParser $element */
        foreach($elements as $element) {
            $this->parseArticleContentElement($content, $element);
        }
        return $content;
    }

    public function parseArticleContentElement(ArticleContent $content, HtmlParser $element): void
    {
        $type = $element->getFirstTagName();
        if($type === 'p') {
            $content->add(new ParagraphArticleContentElement($element->getText()));
        }
        elseif($type == 'ul') {
            $content->addList($element->getListByTagName('li')->getTexts());
        }
        elseif($type == 'blockquote') {
            $content->addQuote($element->getText());
        }
        elseif($type == 'h1') {
            $content->addHeading(1, $element->getText());
        }
        elseif($type == 'h2') {
            $content->addHeading(2, $element->getText());
        }
        elseif($type == 'h3') {
            $content->addHeading(3, $element->getText());
        }
    }

    public function getMainImage(): null|ArticleImage
    {
        $src = $this->getMainImageSrc();
        if($src) {
            $image = new ArticleImage($src);
            $image->setAlt($this->getMainImageAlt());
            $image->setAuthor($this->getMainImageAuthor());
            return $image;
        }
        return null;
    }

    public function makeTime(string $time, string $format, string $timezone = 'UTC'): null|string
    {
        if(empty($time)) {
            return null;
        }
        $dt = DateTime::createFromFormat($format, $time, new DateTimeZone($timezone));
        if(!$dt) {
            return null;
        }

        $dt->setTimezone(new DateTimeZone('UTC'));
        return $dt->format('Y-m-d H:i:s');
    }
}
