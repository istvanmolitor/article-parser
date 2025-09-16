<?php

namespace Molitor\ArticleParser\Services;

use Molitor\ArticleParser\Parsers\ArticleParser;
use Molitor\ArticleParser\Parsers\Hu24ArticleParser;
use Molitor\ArticleParser\Parsers\IndexArticleParser;
use Molitor\ArticleParser\Article\Article;
use Molitor\ArticleParser\Parsers\StoryHuArticleParser;
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
        $article->portal = $parser->getPortal();
        $article->url = $url;
        $article->title = $parser->getTitle();
        $article->mainImage = $parser->getMainImage();
        $article->lead = $parser->getLead();
        $article->keywords = $parser->getKeywords();
        $article->author = $parser->getAuthor();
        $article->content = $parser->getArticleContent();
        $article->createdAt = $parser->getCreatedAt();
        return $article;
    }
}
