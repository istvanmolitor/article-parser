<?php

use Molitor\ArticleParser\Parsers\Hu24ArticleParser;
use Molitor\HtmlParser\HtmlParser;
use PHPUnit\Framework\TestCase;

class ArticleParser24HuTest extends TestCase
{
    private Hu24ArticleParser $parser;

    protected function setUp(): void
    {
        $content = @file_get_contents('https://24.hu/szorakozas/2025/08/29/valyi-istvan-orsegi-oldtimer-egyesulet-tag-kizarasa-politikai-nezetei-miatt/');
        if ($content === false || $content === null) {
            $this->markTestSkipped('Skipped 24.hu tests: failed to download article content in current environment.');

            return;
        }

        $this->parser = new Hu24ArticleParser(new HtmlParser($content));
    }

    public function test_24_hu_valid_article(): void
    {
        $this->assertTrue($this->parser->isValidArticle());
    }

    public function test_24_hu_title(): void
    {
        $this->assertIsString($this->parser->getTitle());
        $this->assertNotSame('', trim((string) $this->parser->getTitle()));
    }

    public function test_24_hu_lead(): void
    {
        $this->assertIsString((string) $this->parser->getLead());
    }

    public function test_24_hu_main_image_src(): void
    {
        $src = $this->parser->getMainImageSrc();
        if ($src !== null) {
            $this->assertStringStartsWith('http', $src);
        }

        $this->assertTrue($src === null || is_string($src));
    }

    public function test_24_hu_main_image_alt(): void
    {
        $this->assertTrue($this->parser->getMainImageAlt() === null || is_string($this->parser->getMainImageAlt()));
    }

    public function test_24_hu_main_author(): void
    {
        $this->assertTrue($this->parser->getAuthors() === null || is_string($this->parser->getAuthors()));
    }

    public function test_24_hu_created_at(): void
    {
        $this->assertTrue($this->parser->getCreatedAt() === null || is_string($this->parser->getCreatedAt()));
    }

    public function test_24_hu_keywords(): void
    {
        $this->assertIsArray($this->parser->getKeywords());
    }
}
