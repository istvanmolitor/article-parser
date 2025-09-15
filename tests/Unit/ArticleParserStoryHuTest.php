<?php

use Molitor\ArticleParser\Parsers\StoryHuArticleParser;
use PHPUnit\Framework\TestCase;
use Molitor\HtmlParser\HtmlParser;

class ArticleParserStoryHuTest extends TestCase
{
    private StoryHuArticleParser $parser;

    public function setUp(): void
    {
        $content = file_get_contents('https://story.hu/cimlapsztori/2025/09/07/racz-gyuricza-dora-en-sem-vagyok-mindig-csipkeben-galeria/');
        $this->parser = new StoryHuArticleParser(new HtmlParser($content));
    }

    public function test_story_hu_valid_article(): void
    {
        $this->assertTrue($this->parser->isValidArticle());
    }

    public function test_story_hu_title(): void
    {
        $title = 'Rácz-Gyuricza Dóra: „Én sem vagyok mindig csipkében” – galériával';
        $this->assertSame($title, $this->parser->getTitle());
    }

    public function test_story_hu_lead(): void {
        $lead = 'Gyuricza Dórával nem fordulhat elő, hogy smink nélkül, kócosan lép ki az utcára. Rácz Jenő feleségének igényessége nem csak önmagának szól.';
        $this->assertSame($lead, $this->parser->getLead());
    }

    public function test_story_hu_main_image_src(): void {
        $src = 'https://story.hu/uploads/2025/09/racz-gyuricza-dora.jpg';
        $this->assertSame($src, $this->parser->getMainImageSrc());
    }

    public function test_story_hu_main_image_alt(): void {
        $this->assertNull($this->parser->getMainImageAlt());
    }

    public function test_story_hu_main_author(): void
    {
        $author = 'Janotka Krisztina';
        $this->assertSame($author, $this->parser->getAuthor());
    }

    public function test_story_hu_created_at(): void
    {
        $this->assertSame('2025-09-06 22:00:00', $this->parser->getCreatedAt());
    }

    public function test_story_hu_keywords(): void
    {
        $this->assertSame([], $this->parser->getKeywords());
    }
}
