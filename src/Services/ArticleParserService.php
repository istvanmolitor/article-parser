<?php

namespace Molitor\ArticleParser\Services;

use Molitor\ArticleParser\Parsers\ArticleParser;
use Molitor\ArticleParser\Parsers\Hu24ArticleParser;
use Molitor\ArticleParser\Parsers\IndexArticleParser;
use Molitor\ArticleScraper\Article\Article;
use Molitor\HtmlParser\HtmlParser;

class ArticleParserService
{
    public function getByUrl(string $url): ?Article
    {
        $content = file_get_contents($url);
        $host = parse_url($url, PHP_URL_HOST);
        switch ($host) {
            case '24.hu':
                $parser = new Hu24ArticleParser(new HtmlParser($content));
            case 'index.hu':
                $parser = new IndexArticleParser(new HtmlParser($content));
            default:
                return null;
        }
        return $this->getArticleByParser($url, $parser);
    }

    private function getArticleByParser(string $url, ArticleParser $parser): ?Article
    {
        $article = new Article();
        $article->title = $parser->getTitle();
        $article->lead = $parser->getLead();
        $article->keywords = $parser->getKeywords();
        $article->author = $parser->getAuthor();
        $article->url = $url;
        return $article;
    }
}
