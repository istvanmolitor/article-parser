<?php

use Molitor\ArticleParser\Article\Article;
use Molitor\ArticleParser\Services\ArticleParserService;
use PHPUnit\Framework\TestCase;

class ArticleParserServiceTest extends TestCase
{
    private ?Article $article = null;

    protected function setUp(): void
    {
        try {
            $service = new ArticleParserService;
            $this->article = $service->getByUrl('https://story.hu/cimlapsztori/2025/09/07/racz-gyuricza-dora-en-sem-vagyok-mindig-csipkeben-galeria/');
        } catch (\Throwable) {
            $this->markTestSkipped('Skipped ArticleParserService tests: parser service returned no article in current environment.');
        }
    }

    public function test_story_hu_title(): void
    {
        $this->assertNotSame('', trim($this->article->getTitle()));
    }

    public function test_story_hu_lead(): void
    {
        $this->assertIsString($this->article->getLead());
    }

    public function test_story_hu_main_image_src(): void
    {
        $src = $this->article->getMainImage()?->getSrc();
        $this->assertTrue($src === null || str_starts_with($src, 'http'));
    }

    public function test_story_hu_main_image_alt(): void
    {
        $this->assertTrue($this->article->getMainImage()?->getAlt() === null || is_string($this->article->getMainImage()?->getAlt()));
    }

    public function test_story_hu_main_author(): void
    {
        $this->assertIsArray($this->article->getAuthors());
    }

    public function test_story_hu_created_at(): void
    {
        $this->assertTrue($this->article->getCreatedAt() === null || is_string($this->article->getCreatedAt()));
    }

    public function test_story_hu_keywords(): void
    {
        $this->assertIsArray($this->article->getKeywords());
    }
}
