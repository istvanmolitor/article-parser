<?php

use Molitor\ArticleParser\Article\Article;
use Molitor\ArticleParser\Services\ArticleParserService;
use PHPUnit\Framework\TestCase;

class ArticleParserServiceTest extends TestCase
{
    private Article $article;

    public function setUp(): void
    {
        $service = new ArticleParserService();
        $this->article = $service->getByUrl('https://story.hu/cimlapsztori/2025/09/07/racz-gyuricza-dora-en-sem-vagyok-mindig-csipkeben-galeria/');
    }

    public function test_story_hu_title(): void
    {
        $title = 'Rácz-Gyuricza Dóra: „Én sem vagyok mindig csipkében” – galériával';
        $this->assertSame($title, $this->article->getTitle());
    }

    public function test_story_hu_lead(): void {
        $lead = 'Gyuricza Dórával nem fordulhat elő, hogy smink nélkül, kócosan lép ki az utcára. Rácz Jenő feleségének igényessége nem csak önmagának szól.';
        $this->assertSame($lead, $this->article->getLead());
    }

    public function test_story_hu_main_image_src(): void {
        $src = 'https://story.hu/uploads/2025/09/racz-gyuricza-dora.jpg';
        $this->assertSame($src, $this->article->getMainImage()->getSrc());
    }

    public function test_story_hu_main_image_alt(): void {
        $this->assertNull($this->article->getMainImage()->getAlt());
    }

    public function test_story_hu_main_author(): void
    {
        $author = 'Janotka Krisztina';
        $this->assertSame([$author], $this->article->getAuthors());
    }

    public function test_story_hu_created_at(): void
    {
        $this->assertSame('2025-09-06 22:00:00', $this->article->getCreatedAt());
    }

    public function test_story_hu_keywords(): void
    {
        $this->assertSame([], $this->article->getKeywords());
    }
}
