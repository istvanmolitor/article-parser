<?php

namespace Molitor\ArticleParser\Parsers;

use Molitor\ArticleParser\Article\ArticleContent;
use Molitor\ArticleParser\Article\ArticleImage;
use Molitor\HtmlParser\HtmlParser;

class IndexArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return 'index.hu';
    }

    public function isValidArticle(): bool
    {
        return true;
    }

    public function getTitle(): string
    {
        return (string)$this->html->getByClass('content-title')?->getText();
    }

    public function getMainImageSrc(): null|string
    {
        return $this->html->getByClass('cikk-cover')?->getByTagName('img')?->getAttribute('src')??null;
    }

    public function getMainImageAlt(): null|string
    {
        return $this->html->getByClass('cikk-cover')?->getByTagName('img')?->getAttribute('alt');
    }

    public function getCreatedAt(): string|null
    {
        return null;
    }

    public function getMainImageAuthor(): string
    {
        return '';
    }

    public function getLead(): string
    {
        return (string)$this->html->getByClass('lead_container')?->getText();
    }

    public function getAuthors(): null|string|array
    {
        return $this->html->getByClass('szerzok_container')?->getByClass('szerzo')?->getText();
    }

    public function getArticleContentWrapper(): null|HtmlParser
    {
        return $this->html->getByClass('cikk-torzs');
    }

    public function parseArticleContentElement(ArticleContent $content, HtmlParser $element): void
    {
        $tagName = $element->getFirstTagName();
        if($tagName === 'div') {
            if($element->classExists('eyecatcher_long')) {
                $content->addHeading($element->getText());
            }
            elseif($element->classExists('szerkfotoimage')) {
                $imageData = $element->getByClass('szerkfotoimage')?->getByTagName('img')?->parseImage();
                if($imageData) {

                    $imageAuthor = $element?->getByClass('photographer')?->getText();
                    if($imageAuthor) {
                        $imageAuthor = substr($imageAuthor, 6);
                    }

                    $image = $content->addImage($imageData['src']);
                    $image->setAlt($imageData['alt'] ?? null);
                    $image->setAuthor($imageAuthor);
                }
            }
            elseif($element->classExists('yt-video-container')) {
                $videoData = $element?->getByTagName('iframe')?->getAttributes();
                if($videoData) {
                    $video = $content->addVideo($videoData['src']);
                }
            }
        }


        parent::parseArticleContentElement($content, $element);
    }
}
