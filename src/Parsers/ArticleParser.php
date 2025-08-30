<?php

namespace Molitor\ArticleParser\Parsers;

use Molitor\ArticleParser\Article\ArticleContent;
use Molitor\ArticleParser\Article\ArticleContentParagraph;
use Molitor\ArticleParser\Article\ArticleImage;
use Molitor\HtmlParser\HtmlParser;

abstract class ArticleParser
{
    public function __construct(
        protected HtmlParser $html
    )
    {
    }

    abstract public function getPortal(): string;

    abstract public function isValidArticle(): bool;

    abstract public function getAuthor(): string;

    public function getTitle(): string {
        $h1 = $this->html->getElementByFirstTagName('h1')->stripTags();
        if($h1) {
            return $h1;
        }
        return $this->html->getElementByFirstTagName('title')->stripTags();
    }

    public function getMainImageSrc(): string {
        $metaData = $this->html->getMetaData();
        if(isset($metaData['og:image'])) {
            return $metaData['og:image'];
        }
        return '';
    }

    abstract public function getMainImageAlt(): string;

    abstract public function getCreatedAt(): string;

    abstract public function getMainImageAuthor(): string;

    public function getLead(): string {
        $metaData = $this->html->getMetaData();
        if(isset($metaData['description'])) {
            return $metaData['description'];
        }
        return '';
    }

    public function getKeywords(): array
    {
        return $this->html->getKeywords();
    }

    abstract public function getArticleContentWrapper(): ?HtmlParser;

    public function getArticleContent(): ArticleContent {
        $articleContentWrapper = $this->getArticleContentWrapper();

        $content = new ArticleContent();
        if(!$articleContentWrapper) {
            return $content;
        }

        $elements = $articleContentWrapper->getChildren();
        foreach($elements as $element) {
            $type = $element->getFirstTagName();
            if($type === 'p') {
                $content->add(new ArticleContentParagraph($element->stripTags()));
            }
            elseif($type == 'ul') {
                $content->addList($element->getElementsByTagName('li'));
            }
            elseif($type == 'blockquote') {
                $content->addQuote($element->stripTags());
            }
            elseif($type == 'h1') {
                $content->addHeading(1, $element->stripTags());
            }
            elseif($type == 'h2') {
                $content->addHeading(2, $element->stripTags());
            }
            elseif($type == 'h3') {
                $content->addHeading(3, $element->stripTags());
            }

        }
        return $content;
    }

    public function getMainImage(): ?ArticleImage
    {
        $src = $this->getMainImageSrc();
        if($src) {
            $image = new ArticleImage();
            $image->src = $src;
            $image->alt = $this->getMainImageAlt();
            $image->author = $this->getMainImageAuthor();
            return $image;
        }
        return null;
    }
}
