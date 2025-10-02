<?php

namespace Molitor\ArticleParser\Services;

use Molitor\ArticleParser\Parsers\ArticleParser;
use Molitor\ArticleParser\Parsers\Hu24ArticleParser;
use Molitor\ArticleParser\Parsers\IndexArticleParser;
use Molitor\ArticleParser\Article\Article;
use Molitor\ArticleParser\Parsers\StoryHuArticleParser;
use Molitor\ArticleParser\Parsers\TelexArticleParser;
use Molitor\HtmlParser\HtmlParser;

class ArticleParserService
{
    public function getByUrl(string $url): Article|null
    {
        $content = @file_get_contents($url);
        if(!$content) {
            return null;
        }
        $host = parse_url($url, PHP_URL_HOST);
        $html = new HtmlParser($content);

        switch ($host) {
            case '24.hu':
                $parser = new Hu24ArticleParser($html);
                break;
            case 'index.hu':
                $parser = new IndexArticleParser($html);
                break;
            case 'story.hu':
                $parser = new StoryHuArticleParser($html);
                break;
            case 'telex.hu':
                $parser = new TelexArticleParser($html);
                break;
            default:
                return null;
        }
        return $this->getArticleByParser($url, $parser);
    }

    private function getArticleByParser(string $url, ArticleParser $parser): Article|null
    {
        if(!$parser->isValidArticle()) {
            return null;
        }
        $article = new Article();
        $article->setPortal($parser->getPortal());
        $article->setUrl($url);
        $article->setTitle($parser->getTitle());
        $article->setMainImage($parser->getMainImage());
        $article->setLead($parser->getLead());
        $article->setContent($parser->getArticleContent());
        $article->setCreatedAt($parser->getCreatedAt());

        $author = $parser->getAuthors();
        if(is_array($author)) {
            foreach($author as $authorName) {
                $article->addAuthor($authorName);
            }
        }
        elseif(is_string($author)) {
            $article->addAuthor($author);
        }

        foreach ($parser->getKeywords() as $keyword) {
            $article->addKeyword($keyword);
        }

        return $article;
    }
}
