<?php

namespace Molitor\ArticleParser\Services;

use Molitor\ArticleParser\Parsers\ArticleParser;
use Molitor\ArticleParser\Parsers\Hu24ArticleParser;
use Molitor\ArticleParser\Parsers\Hu444ArticleParser;
use Molitor\ArticleParser\Parsers\IndexArticleParser;
use Molitor\ArticleParser\Article\Article;
use Molitor\ArticleParser\Parsers\StoryHuArticleParser;
use Molitor\ArticleParser\Parsers\TelexArticleParser;
use Molitor\HtmlParser\HtmlParser;

class ArticleParserService
{
    private array $map = [
        '24.hu' => Hu24ArticleParser::class,
        'index.hu' => IndexArticleParser::class,
        'story.hu' => StoryHuArticleParser::class,
        'telex.hu' => TelexArticleParser::class,
        '444.hu' => Hu444ArticleParser::class,
    ];

    private function getHost(string $url): string
    {
        return parse_url($url, PHP_URL_HOST);
    }

    public function isValidUrl(string $url): bool
    {
        $host = $this->getHost($url);
        return array_key_exists($host, $this->map);
    }

    public function getParser(string $url): ArticleParser|null
    {
        if(!$this->isValidUrl($url)) {
            return null;
        }

        $content = @file_get_contents($url);
        if(!$content) {
            return null;
        }

        $host = $this->getHost($url);
        $class = $this->map[$host];

        return new $class(new HtmlParser($content));
    }

    public function getByUrl(string $url): Article|null
    {
        if(!$this->isValidUrl($url)) {
            return null;
        }

        $content = @file_get_contents($url);
        if(!$content) {
            return null;
        }

        $parser = $this->getParser($url);
        if(!$parser) {
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
