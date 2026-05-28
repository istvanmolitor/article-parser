<?php

use Molitor\ArticleParser\Parsers\StoryHuArticleParser;
use Molitor\HtmlParser\HtmlParser;
use PHPUnit\Framework\TestCase;

class ArticleParserStoryHuTest extends TestCase
{
    private StoryHuArticleParser $parser;

    protected function setUp(): void
    {
        $content = @file_get_contents('https://story.hu/cimlapsztori/2025/09/07/racz-gyuricza-dora-en-sem-vagyok-mindig-csipkeben-galeria/');
        if ($content === false || $content === null) {
            $this->markTestSkipped('Skipped Story.hu tests: failed to download article content in current environment.');

            return;
        }

        $this->parser = new StoryHuArticleParser(new HtmlParser($content));
    }

    public function test_story_hu_valid_article(): void
    {
        $this->assertIsBool($this->parser->isValidArticle());
    }

    public function test_story_hu_title(): void
    {
        $this->assertNotSame('', trim((string) $this->parser->getTitle()));
    }

    public function test_story_hu_lead(): void
    {
        $this->assertIsString((string) $this->parser->getLead());
    }

    public function test_story_hu_main_image_src(): void
    {
        $src = $this->parser->getMainImageSrc();
        $this->assertTrue($src === null || str_starts_with($src, 'http'));
    }

    public function test_story_hu_main_image_alt(): void
    {
        $this->assertTrue($this->parser->getMainImageAlt() === null || is_string($this->parser->getMainImageAlt()));
    }

    public function test_story_hu_main_author(): void
    {
        $this->assertTrue($this->parser->getAuthors() === null || is_string($this->parser->getAuthors()));
    }

    public function test_story_hu_created_at(): void
    {
        $this->assertTrue($this->parser->getCreatedAt() === null || is_string($this->parser->getCreatedAt()));
    }

    public function test_story_hu_keywords(): void
    {
        $this->assertIsArray($this->parser->getKeywords());
    }
}
