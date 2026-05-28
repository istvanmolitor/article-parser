<?php

use Molitor\ArticleParser\Parsers\TelexArticleParser;
use Molitor\HtmlParser\HtmlParser;
use PHPUnit\Framework\TestCase;

class ArticleParserTelexHuTest extends TestCase
{
    private TelexArticleParser $parser;

    protected function setUp(): void
    {
        $url = 'https://telex.hu/belfold/2025/09/24/szolo-utcai-javitointezet-jelentes-tuzson-bence-igazsagugyi-miniszter';
        $content = @file_get_contents($url);
        if ($content === false || $content === null) {
            $this->markTestSkipped('Skipped Telex tests: failed to download article content in current environment.');

            return;
        }
        $this->parser = new TelexArticleParser(new HtmlParser($content));
    }

    public function test_telex_valid_article(): void
    {
        $this->assertTrue($this->parser->isValidArticle());
    }

    public function test_telex_title(): void
    {
        $this->assertNotSame('', trim((string) $this->parser->getTitle()));
    }

    public function test_telex_lead(): void
    {
        $this->assertIsString((string) $this->parser->getLead());
    }

    public function test_telex_main_image_src(): void
    {
        $src = $this->parser->getMainImageSrc();
        $this->assertTrue($src === null || str_starts_with($src, 'http'));
    }

    public function test_telex_main_image_alt(): void
    {
        $this->assertTrue($this->parser->getMainImageAlt() === null || is_string($this->parser->getMainImageAlt()));
    }

    public function test_telex_main_author(): void
    {
        $this->assertTrue($this->parser->getAuthors() === null || is_string($this->parser->getAuthors()));
    }

    public function test_telex_created_at(): void
    {
        $this->assertTrue($this->parser->getCreatedAt() === null || is_string($this->parser->getCreatedAt()));
    }

    public function test_telex_keywords(): void
    {
        $this->assertIsArray($this->parser->getKeywords());
    }
}
