<?php

namespace Molitor\ArticleParser\Parsers;

use Molitor\ArticleScraper\Article\ArticleContent;
use Molitor\ArticleScraper\Article\ArticleContentParagraph;
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

    abstract public function getTitle(): string;

    abstract public function getMainImageSrc(): string;

    abstract public function getMainImageAlt(): string;

    abstract public function getCreatedAt(): string;

    abstract public function getMainImageAuthor(): string;

    abstract public function getLead(): string;

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
        }
        return $content;
    }
}
