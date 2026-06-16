<?php

namespace Molitor\ArticleParser\Services;

use Molitor\ArticleParser\Article\Article;
use Molitor\ArticleParser\Exceptions\ArticleFetchException;
use Molitor\ArticleParser\Exceptions\InvalidArticleException;
use Molitor\ArticleParser\Exceptions\UnsupportedUrlException;
use Molitor\ArticleParser\Parsers\ArticleParser;
use Molitor\ArticleParser\Parsers\FeminaArticleParser;
use Molitor\ArticleParser\Parsers\GlamourArticleParser;
use Molitor\ArticleParser\Parsers\Hu24ArticleParser;
use Molitor\ArticleParser\Parsers\NepszavaArticleParser;
use Molitor\ArticleParser\Parsers\Hu444ArticleParser;
use Molitor\ArticleParser\Parsers\IndexArticleParser;
use Molitor\ArticleParser\Parsers\KiskegyedArticleParser;
use Molitor\ArticleParser\Parsers\OrigoArticleParser;
use Molitor\ArticleParser\Parsers\PortArticleParser;
use Molitor\ArticleParser\Parsers\PortfolioArticleParser;
use Molitor\ArticleParser\Parsers\ProhardverArticleParser;
use Molitor\ArticleParser\Parsers\StoryHuArticleParser;
use Molitor\ArticleParser\Parsers\TelexArticleParser;
use Molitor\HtmlParser\HtmlParser;

class ArticleParserService
{
    private array $map = [
        '24.hu' => Hu24ArticleParser::class,
        'femina.hu' => FeminaArticleParser::class,
        'glamour.hu' => GlamourArticleParser::class,
        'nepszava.hu' => NepszavaArticleParser::class,
        'index.hu' => IndexArticleParser::class,
        'kiskegyed.hu' => KiskegyedArticleParser::class,
        'origo.hu' => OrigoArticleParser::class,
        'port.hu' => PortArticleParser::class,
        'portfolio.hu' => PortfolioArticleParser::class,
        'prohardver.hu' => ProhardverArticleParser::class,
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

    /** @throws UnsupportedUrlException */
    private function resolveParserClass(string $url): string
    {
        $host = $this->getHost($url);

        if ($host === '') {
            throw new UnsupportedUrlException("Invalid URL: {$url}");
        }

        $normalizedHost = preg_replace('/^www\./', '', $host);

        if (! is_string($normalizedHost) || $normalizedHost === '') {
            throw new UnsupportedUrlException("Could not normalize host for URL: {$url}");
        }

        if (array_key_exists($normalizedHost, $this->map)) {
            return $this->map[$normalizedHost];
        }

        foreach ($this->map as $supportedHost => $parserClass) {
            if (str_ends_with($normalizedHost, '.'.$supportedHost)) {
                return $parserClass;
            }
        }

        throw new UnsupportedUrlException("No parser found for host: {$normalizedHost}");
    }

    public function isValidUrl(string $url): bool
    {
        try {
            $this->resolveParserClass($url);

            return true;
        } catch (UnsupportedUrlException) {
            return false;
        }
    }

    /** @throws UnsupportedUrlException|ArticleFetchException */
    public function getParser(string $url): ArticleParser
    {
        $class = $this->resolveParserClass($url);

        $content = @file_get_contents($url);
        if (! $content) {
            throw new ArticleFetchException("Failed to fetch content from URL: {$url}");
        }

        return new $class(new HtmlParser($content));
    }

    /** @throws UnsupportedUrlException|ArticleFetchException|InvalidArticleException */
    public function getByUrl(string $url): Article
    {
        $parser = $this->getParser($url);

        return $this->getArticleByParser($url, $parser);
    }

    /** @throws InvalidArticleException */
    private function getArticleByParser(string $url, ArticleParser $parser): Article
    {
        if (! $parser->isValidArticle()) {
            throw new InvalidArticleException("URL does not point to a valid article: {$url}");
        }

        $article = new Article;
        $article->setPortal($parser->getPortal());
        $article->setUrl($url);
        $article->setTitle($parser->getTitle());
        $article->setMainImage($parser->getMainImage());
        $article->setLead($parser->getLead());
        $article->setLanguage($parser->getLanguage() ?? 'hu');
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
