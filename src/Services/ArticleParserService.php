<?php

namespace Molitor\ArticleParser\Services;

use Molitor\ArticleParser\Article\Article;
use Molitor\ArticleParser\Parsers\ArticleParser;
use Molitor\ArticleParser\Parsers\Hu24ArticleParser;
use Molitor\ArticleParser\Parsers\Hu444ArticleParser;
use Molitor\ArticleParser\Parsers\IndexArticleParser;
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
        $host = parse_url($url, PHP_URL_HOST);

        if (! is_string($host)) {
            return '';
        }

        return strtolower($host);
    }

    private function resolveParserClass(string $url): ?string
    {
        $host = $this->getHost($url);

        if ($host === '') {
            return null;
        }

        $normalizedHost = preg_replace('/^www\./', '', $host);

        if (! is_string($normalizedHost) || $normalizedHost === '') {
            return null;
        }

        if (array_key_exists($normalizedHost, $this->map)) {
            return $this->map[$normalizedHost];
        }

        foreach ($this->map as $supportedHost => $parserClass) {
            if (str_ends_with($normalizedHost, '.'.$supportedHost)) {
                return $parserClass;
            }
        }

        return null;
    }

    public function isValidUrl(string $url): bool
    {
        return $this->resolveParserClass($url) !== null;
    }

    public function getParser(string $url): ?ArticleParser
    {
        $class = $this->resolveParserClass($url);
        if (! $class) {
            return null;
        }

        $content = @file_get_contents($url);
        if (! $content) {
            return null;
        }

        return new $class(new HtmlParser($content));
    }

    public function getByUrl(string $url): ?Article
    {

        $parser = $this->getParser($url);
        if (! $parser) {
            return null;
        }

        return $this->getArticleByParser($url, $parser);
    }

    private function getArticleByParser(string $url, ArticleParser $parser): ?Article
    {
        if (! $parser->isValidArticle()) {
            return null;
        }
        $article = new Article;
        $article->setPortal($parser->getPortal());
        $article->setUrl($url);
        $article->setTitle($parser->getTitle());
        $article->setMainImage($parser->getMainImage());
        $article->setLead($parser->getLead());
        $article->setContent($parser->getArticleContent());
        $article->setCreatedAt($parser->getCreatedAt());

        $author = $parser->getAuthors();
        if (is_array($author)) {
            foreach ($author as $authorName) {
                $article->addAuthor($authorName);
            }
        } elseif (is_string($author)) {
            $article->addAuthor($author);
        }

        foreach ($parser->getKeywords() as $keyword) {
            $article->addKeyword($keyword);
        }

        return $article;
    }
}
