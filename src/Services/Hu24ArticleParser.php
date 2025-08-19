<?php

namespace Molitor\ArticleParser\Services;

use Molitor\HtmlParser\HtmlParser;

class Hu24ArticleParser extends ArticleParser
{
    public function getPortal(): string
    {
        return '24.hu';
    }

    public function getSlug(): string
    {
        return $this->url->getPath();
    }

    public function isValidArticle(): bool
    {
        return $this->html->classExists('hir24-post');
    }

    public function getTitle(): string
    {
        return $this->html->getElementByFirstClassName('o-post__title')->stripTags();
    }

    public function getMainImageSrc(): string
    {
        return $this->html->getElementByFirstClassName('o-post__featuredImage')->getAttribute('src');
    }

    public function getMainImageAlt(): string
    {
        return $this->html->getElementByFirstClassName('o-post__featuredImage')->getAttribute('alt');
    }

    public function getCreatedAt(): string
    {
        return $this->html->getElementByFirstClassName('o-post__date')->stripTags();
    }

    public function getMainImageAuthor(): string
    {
        return '';
    }

    public function getLead(): string
    {
        $pTag = $this->html->getElementByFirstClassName('u-onlyArticlePages')->getElementByFirstTagName('p');
        if($pTag) {
            return $pTag->stripTags();
        }
        return '';
    }

    public function getBody(): array
    {
        $body = [];
        $elements = $this->html->getElementByFirstClassName('u-onlyArticlePages')->getChildren();

        /** @var HtmlParser $element */
        foreach($elements as $element) {
            $type = $element->getFirstTagName();
            if($type === 'p') {
                $body[] = [
                    'type' => 'paragraph',
                    'content' => $element->stripTags(),
                ];
            }
            elseif($type === 'ul') {
                $items = [];
                foreach ($element->getElementsByTagName('li') as $li) {
                    $items[] = $li->stripTags();
                }
                $body[] = [
                    'type' => 'list',
                    'items' => $items,
                ];
            }
        }
        return $body;
    }

    public function getAuthor(): string
    {
        return '';
    }
}
