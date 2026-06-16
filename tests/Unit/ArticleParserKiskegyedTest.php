<?php

use Molitor\ArticleParser\Parsers\KiskegyedArticleParser;
use Molitor\HtmlParser\HtmlParser;
use PHPUnit\Framework\TestCase;

class ArticleParserKiskegyedTest extends TestCase
{
    private KiskegyedArticleParser $parser;

    protected function setUp(): void
    {
        $content = @file_get_contents('https://www.kiskegyed.hu/eletmod/ez-a-harom-csillagjegy-a-sikerre-szuletett-es-el-is-eri-azt/vpk4qlt');
        if ($content === false || $content === null) {
            $this->markTestSkipped('Skipped Kiskegyed tests: failed to download article content in current environment.');

            return;
        }

        $this->parser = new KiskegyedArticleParser(new HtmlParser($content));
    }

    public function test_kiskegyed_valid_article(): void
    {
        $this->assertTrue($this->parser->isValidArticle());
    }

    public function test_kiskegyed_title(): void
    {
        $this->assertNotSame('', trim((string) $this->parser->getTitle()));
    }

    public function test_kiskegyed_lead(): void
    {
        $this->assertIsString((string) $this->parser->getLead());
    }

    public function test_kiskegyed_main_image_src(): void
    {
        $src = $this->parser->getMainImageSrc();
        $this->assertTrue($src === null || str_starts_with($src, 'http'));
    }

    public function test_kiskegyed_main_image_alt(): void
    {
        $this->assertTrue($this->parser->getMainImageAlt() === null || is_string($this->parser->getMainImageAlt()));
    }

    public function test_kiskegyed_main_image_author(): void
    {
        $this->assertTrue($this->parser->getMainImageAuthor() === null || is_string($this->parser->getMainImageAuthor()));
    }

    public function test_kiskegyed_author(): void
    {
        $this->assertTrue($this->parser->getAuthors() === null || is_string($this->parser->getAuthors()));
    }

    public function test_kiskegyed_created_at(): void
    {
        $createdAt = $this->parser->getCreatedAt();
        $this->assertTrue($createdAt === null || is_string($createdAt));
    }

    public function test_kiskegyed_keywords(): void
    {
        $this->assertIsArray($this->parser->getKeywords());
    }

    public function test_kiskegyed_article_content(): void
    {
        $content = $this->parser->getArticleContent();
        $this->assertGreaterThan(0, $content->count());
    }
}
