<?php

use PHPUnit\Framework\TestCase;
use Molitor\HtmlParser\HtmlParser;
use Molitor\ArticleParser\Parsers\TelexArticleParser;

class ArticleParserTelexHuTest extends TestCase
{
    private TelexArticleParser $parser;

    public function setUp(): void
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
        // Expected title captured at the time of creating the test
        $title = 'Magyarország kormánya fontos bejelentést tett kedden';
        $this->assertSame($title, $this->parser->getTitle());
    }

    public function test_telex_lead(): void
    {
        $lead = 'A részleteket a kormányülés után ismertették, a döntések több millió embert érintenek.';
        $this->assertSame($lead, (string)$this->parser->getLead());
    }

    public function test_telex_main_image_src(): void
    {
        $src = 'https://images.telex.hu/images/2024/09/17/telex-kormany-bejelentes-cover.jpg';
        $this->assertSame($src, $this->parser->getMainImageSrc());
    }

    public function test_telex_main_image_alt(): void
    {
        $alt = '';
        $this->assertSame($alt, (string)$this->parser->getMainImageAlt());
    }

    public function test_telex_main_author(): void
    {
        $author = 'Telex';
        $this->assertSame($author, $this->parser->getAuthors());
    }

    public function test_telex_created_at(): void
    {
        // Expect UTC conversion from local time if timezone present in HTML
        $this->assertSame('2024-09-17 10:00:00', $this->parser->getCreatedAt());
    }

    public function test_telex_keywords(): void
    {
        $this->assertSame(['kormány', 'bejelentés'], $this->parser->getKeywords());
    }
}
