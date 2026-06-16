<?php

use Molitor\ArticleParser\Parsers\ProhardverArticleParser;
use Molitor\HtmlParser\HtmlParser;
use PHPUnit\Framework\TestCase;

class ArticleParserProhardverTest extends TestCase
{
    private ProhardverArticleParser $parser;

    protected function setUp(): void
    {
        $url = 'https://prohardver.hu/hir/ez_a_xiaomi_robot_az_otthoni_villanyauto_toltest_i.html';
        $content = @file_get_contents($url);
        if ($content === false || $content === null) {
            $this->markTestSkipped('Skipped Prohardver tests: failed to download article content in current environment.');

            return;
        }
        $this->parser = new ProhardverArticleParser(new HtmlParser($content));
    }

    public function test_prohardver_valid_article(): void
    {
        $this->assertTrue($this->parser->isValidArticle());
    }

    public function test_prohardver_title(): void
    {
        $this->assertNotSame('', trim((string) $this->parser->getTitle()));
    }

    public function test_prohardver_lead(): void
    {
        $lead = $this->parser->getLead();
        $this->assertIsString($lead);
        $this->assertNotSame('', trim($lead));
    }

    public function test_prohardver_main_image_src(): void
    {
        $src = $this->parser->getMainImageSrc();
        $this->assertTrue($src === null || str_starts_with($src, 'http'));
    }

    public function test_prohardver_main_image_alt(): void
    {
        $this->assertNull($this->parser->getMainImageAlt());
    }

    public function test_prohardver_main_image_author(): void
    {
        $this->assertNull($this->parser->getMainImageAuthor());
    }

    public function test_prohardver_author(): void
    {
        $author = $this->parser->getAuthors();
        $this->assertNotEmpty($author);
    }

    public function test_prohardver_created_at(): void
    {
        $createdAt = $this->parser->getCreatedAt();
        $this->assertIsString($createdAt);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $createdAt);
    }

    public function test_prohardver_keywords(): void
    {
        $this->assertIsArray($this->parser->getKeywords());
    }

    public function test_prohardver_portal(): void
    {
        $this->assertSame('prohardver.hu', $this->parser->getPortal());
    }

    public function test_prohardver_article_content(): void
    {
        $content = $this->parser->getArticleContent();
        $this->assertGreaterThan(0, $content->count());
    }
}
