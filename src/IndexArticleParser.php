<?php

namespace Molitor\ArticleParser;

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
        return $this->html->getElementByFirstClassName('content-title')->stripTags();
    }

    public function getMainImageSrc(): string
    {
        return $this->html->getElementByFirstClassName('cikk-cover')->getElementByFirstTagName('img')->getAttribute('src');
    }

    public function getMainImageAlt(): string
    {
        return $this->html->getElementByFirstClassName('cikk-cover')->getElementByFirstTagName('img')->getAttribute('alt');
    }

    public function getCreatedAt(): string
    {
        return '';
    }

    public function getMainImageAuthor(): string
    {
        return '';
    }

    public function getLead(): string
    {
        return $this->html->getElementByFirstClassName('lead_container')->stripTags();
    }

    public function getBody(): array
    {
        $body = [];
        $elements = $this->html->getElementByFirstClassName('cikk-torzs')->getElementsByTagName('p');
        foreach($elements as $element) {
            $type = $element->getFirstTagName();
            if($type === 'p') {
                $body[] = [
                    'type' => 'paragraph',
                    'content' => $element->stripTags(),
                ];
            }
            elseif($type == 'ul') {
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
