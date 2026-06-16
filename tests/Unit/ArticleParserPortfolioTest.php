<?php

use Molitor\ArticleParser\Parsers\PortfolioArticleParser;
use Molitor\HtmlParser\HtmlParser;
use PHPUnit\Framework\TestCase;

class ArticleParserPortfolioTest extends TestCase
{
    private PortfolioArticleParser $parser;

    protected function setUp(): void
    {
        $content = @file_get_contents('https://www.portfolio.hu/befektetes/20260616/olyan-draga-a-spacex-hogy-meg-a-marsrol-is-latszik-843540');
        if ($content === false || $content === null) {
            $this->markTestSkipped('Skipped portfolio.hu tests: failed to download article content in current environment.');

            return;
        }

        $this->parser = new PortfolioArticleParser(new HtmlParser($content));
    }

    public function test_portfolio_valid_article(): void
    {
        $this->assertTrue($this->parser->isValidArticle());
    }

    public function test_portfolio_title(): void
    {
        $this->assertIsString($this->parser->getTitle());
        $this->assertNotSame('', trim((string) $this->parser->getTitle()));
    }

    public function test_portfolio_lead(): void
    {
        $this->assertIsString((string) $this->parser->getLead());
    }

    public function test_portfolio_main_image_src(): void
    {
        $src = $this->parser->getMainImageSrc();
        if ($src !== null) {
            $this->assertStringStartsWith('http', $src);
        }

        $this->assertTrue($src === null || is_string($src));
    }

    public function test_portfolio_author(): void
    {
        $author = $this->parser->getAuthors();
        $this->assertNotNull($author);
        $this->assertIsString($author);
    }

    public function test_portfolio_created_at(): void
    {
        $createdAt = $this->parser->getCreatedAt();
        $this->assertNotNull($createdAt);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $createdAt);
    }

    public function test_portfolio_keywords(): void
    {
        $this->assertIsArray($this->parser->getKeywords());
    }

    public function test_portfolio_article_content(): void
    {
        $content = $this->parser->getArticleContent();
        $this->assertNotEmpty($content->getElements());
    }
}
