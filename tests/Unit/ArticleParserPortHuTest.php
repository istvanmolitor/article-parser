<?php

use Molitor\ArticleParser\Parsers\PortArticleParser;
use Molitor\HtmlParser\HtmlParser;
use PHPUnit\Framework\TestCase;

class ArticleParserPortHuTest extends TestCase
{
    private PortArticleParser $parser;

    protected function setUp(): void
    {
        $url = 'https://port.hu/cikk/mozi/uwe-boll-azt-allitja-betiltottak-nemetorszagban-az-armie-hammerrel-kozos-filmjet/article-127732';
        $content = @file_get_contents($url);
        if ($content === false || $content === null) {
            $this->markTestSkipped('Skipped Port.hu tests: failed to download article content in current environment.');

            return;
        }
        $this->parser = new PortArticleParser(new HtmlParser($content));
    }

    public function test_port_valid_article(): void
    {
        $this->assertTrue($this->parser->isValidArticle());
    }

    public function test_port_title(): void
    {
        $this->assertNotSame('', trim((string) $this->parser->getTitle()));
    }

    public function test_port_lead(): void
    {
        $this->assertIsString((string) $this->parser->getLead());
        $this->assertNotSame('', trim((string) $this->parser->getLead()));
    }

    public function test_port_main_image_src(): void
    {
        $src = $this->parser->getMainImageSrc();
        $this->assertTrue($src === null || str_starts_with($src, 'http'));
    }

    public function test_port_main_image_alt(): void
    {
        $this->assertNull($this->parser->getMainImageAlt());
    }

    public function test_port_main_image_author(): void
    {
        $this->assertNull($this->parser->getMainImageAuthor());
    }

    public function test_port_author(): void
    {
        $author = $this->parser->getAuthors();
        $this->assertTrue($author === null || is_string($author) || is_array($author));
        $this->assertNotEmpty($author);
    }

    public function test_port_created_at(): void
    {
        $createdAt = $this->parser->getCreatedAt();
        $this->assertIsString($createdAt);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $createdAt);
    }

    public function test_port_keywords(): void
    {
        $keywords = $this->parser->getKeywords();
        $this->assertIsArray($keywords);
        $this->assertNotEmpty($keywords);
    }

    public function test_port_article_content(): void
    {
        $content = $this->parser->getArticleContent();
        $this->assertGreaterThan(0, $content->count());
    }

    public function test_port_portal(): void
    {
        $this->assertSame('port.hu', $this->parser->getPortal());
    }
}
