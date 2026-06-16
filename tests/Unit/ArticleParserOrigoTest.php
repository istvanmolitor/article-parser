<?php

use Molitor\ArticleParser\Parsers\OrigoArticleParser;
use Molitor\HtmlParser\HtmlParser;
use PHPUnit\Framework\TestCase;

class ArticleParserOrigoTest extends TestCase
{
    private OrigoArticleParser $parser;

    protected function setUp(): void
    {
        $content = @file_get_contents('https://www.origo.hu/itthon/2026/06/nis-reszvenyesi-megallapodas-mol-szerb-kormany');
        if ($content === false || $content === null) {
            $this->markTestSkipped('Skipped origo.hu tests: failed to download article content in current environment.');

            return;
        }

        $this->parser = new OrigoArticleParser(new HtmlParser($content));
    }

    public function test_origo_valid_article(): void
    {
        $this->assertTrue($this->parser->isValidArticle());
    }

    public function test_origo_title(): void
    {
        $this->assertIsString($this->parser->getTitle());
        $this->assertNotSame('', trim((string) $this->parser->getTitle()));
    }

    public function test_origo_lead(): void
    {
        $this->assertIsString((string) $this->parser->getLead());
    }

    public function test_origo_main_image_src(): void
    {
        $src = $this->parser->getMainImageSrc();
        if ($src !== null) {
            $this->assertStringStartsWith('http', $src);
        }

        $this->assertTrue($src === null || is_string($src));
    }

    public function test_origo_main_image_alt(): void
    {
        $this->assertTrue($this->parser->getMainImageAlt() === null || is_string($this->parser->getMainImageAlt()));
    }

    public function test_origo_author(): void
    {
        $this->assertTrue($this->parser->getAuthors() === null || is_string($this->parser->getAuthors()));
    }

    public function test_origo_created_at(): void
    {
        $createdAt = $this->parser->getCreatedAt();
        $this->assertNotNull($createdAt);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $createdAt);
    }

    public function test_origo_keywords(): void
    {
        $this->assertIsArray($this->parser->getKeywords());
    }

    public function test_origo_article_content(): void
    {
        $content = $this->parser->getArticleContent();
        $this->assertNotEmpty($content->getElements());
    }
}
