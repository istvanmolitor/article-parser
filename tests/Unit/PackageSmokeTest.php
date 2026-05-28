<?php

namespace Molitor\ArticleParser\Tests\Unit;

use Molitor\ArticleParser\Article\Article;
use Tests\TestCase;

class PackageSmokeTest extends TestCase
{
    public function test_article_class_is_usable(): void
    {
        $article = new Article;

        $this->assertSame([], $article->getAuthors());
        $this->assertNotNull($article->getContent());
    }
}

