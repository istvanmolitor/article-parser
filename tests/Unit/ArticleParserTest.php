<?php

namespace Unit;

use Molitor\ArticleParser\Services\ArticleParserService;
use PHPUnit\Framework\TestCase;
use Molitor\HtmlParser\HtmlParser;
use Molitor\ArticleParser\Parsers\Hu24ArticleParser;

class ArticleParserTest extends TestCase
{
    private ArticleParserService $parser;

    public function setUp(): void
    {
        $this->parser = new ArticleParserService();
    }

    public function test_article_url_to_array(): void
    {
        $url = 'https://24.hu/szorakozas/2025/08/29/valyi-istvan-orsegi-oldtimer-egyesulet-tag-kizarasa-politikai-nezetei-miatt/';
        $article = $this->parser->getByUrl($url);

        var_dump($article->toArray());
        exit;

        $this->assertSame([], $article->toArray());
    }
}
