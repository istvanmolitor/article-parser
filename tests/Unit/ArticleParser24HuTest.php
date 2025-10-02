<?php

use PHPUnit\Framework\TestCase;
use Molitor\HtmlParser\HtmlParser;
use Molitor\ArticleParser\Parsers\Hu24ArticleParser;

class ArticleParser24HuTest extends TestCase
{
    private Hu24ArticleParser $parser;

    public function setUp(): void
    {
        $content = file_get_contents('https://24.hu/szorakozas/2025/08/29/valyi-istvan-orsegi-oldtimer-egyesulet-tag-kizarasa-politikai-nezetei-miatt/');
        $this->parser = new Hu24ArticleParser(new HtmlParser($content));
    }

    public function test_24_hu_valid_article(): void
    {
        $this->assertTrue($this->parser->isValidArticle());
    }

    public function test_24_hu_title(): void
    {
        $title = 'Vályi István reagált arra, hogy politikai nézetei miatt kizártak egy tagot az Őrségi Oldtimer Egyesületből';
        $this->assertSame($title, $this->parser->getTitle());
    }

    public function test_24_hu_lead(): void {
        $lead = 'Vályi István autós újságíró pénteken Facebook-oldalán osztotta újra azt a neten terjedő levelet, melyben Bencsec István, az Őrségi Nemzetközi Oldtimer Egyesület elnöke közli az egyik taggal, hogy mivel több alkalommal is megszegte azt az egyesületi szabályt, miszerint nem lehetséges a szervezet keretein belül ellenzéki pártok reklámozása, követése, vele való szimpatizálás nyilvános helyeken, illetve nyilvános online platformokon, így szeptember 1-től a tagságát megszüntetik. A levél azt is tartalmazza, hogy az egyesületnek, így annak tagjainak is mindenkor tisztelnünk kell Magyarország Kormányát, így a Fidesz-KDNP pártját.';
        $this->assertSame($lead, $this->parser->getLead());
    }

    public function test_24_hu_main_image_src(): void {
        $src = 'https://s.24.hu/app/uploads/2025/08/central-0856744730-e1756457456551-1024x576.jpg';
        $this->assertSame($src, $this->parser->getMainImageSrc());
    }

    public function test_24_hu_main_image_alt(): void {
        $alt = '';
        $this->assertSame($alt, $this->parser->getMainImageAlt());
    }

    public function test_24_hu_main_author(): void
    {
        $author = 'Csontos Kata';
        $this->assertSame($author, $this->parser->getAuthors());
    }

    public function test_24_hu_created_at(): void
    {
        $this->assertSame('2025-08-29 08:54:00', $this->parser->getCreatedAt());
    }

    public function test_24_hu_keywords(): void
    {
        $this->assertSame([
            'Belföld',
            'jelentés',
            'Igazságügyi Minisztérium',
            'Tuzson Bence',
            'Kuslits Gábor',
            'Szőlő utcai javítóintézet',
        ], $this->parser->getKeywords());
    }
}
