# Article Parser

A small PHP library that extracts structured article data (title, authors, lead, main image, keywords, content elements, publication time) from supported news/article pages.

Currently supported portals:
- 24.hu
- index.hu
- story.hu

## Requirements
- PHP 8.2+
- Composer

## Installation
Install via Composer:

```bash
composer require istvanmolitor/article-parser
```

This package depends on istvanmolitor/html-parser and will be installed automatically.

## Usage
Basic example using the service that selects the proper parser based on the URL host:

```php
use Molitor\ArticleParser\Services\ArticleParserService;

$service = new ArticleParserService();
$article = $service->getByUrl('https://24.hu/...');

if ($article === null) {
    // Unsupported host, network error, or not a valid article page
    exit('Could not parse article');
}

// Work with the Article object
echo $article->getTitle();
print_r($article->toArray());
```

You can also use a concrete parser directly if you already have the HTML content and know the source portal:

```php
use Molitor\HtmlParser\HtmlParser;
use Molitor\ArticleParser\Parsers\Hu24ArticleParser; // or IndexArticleParser, StoryHuArticleParser

$html = new HtmlParser($htmlString);
$parser = new Hu24ArticleParser($html);

if ($parser->isValidArticle()) {
    $title   = $parser->getTitle();
    $lead    = $parser->getLead();
    $authors = $parser->getAuthors();
    $image   = $parser->getMainImage(); // returns ArticleImage|null
    $content = $parser->getArticleContent(); // returns ArticleContent
}
```

### Returned data
Calling Article::toArray() returns a structure like:

```php
[
  'portal'    => '24.hu',
  'url'       => 'https://24.hu/...',
  'title'     => '...',
  'authors'   => ['Author Name'],
  'mainImage' => [ 'src' => '...', 'alt' => '...', 'author' => '...' ],
  'lead'      => '...',
  'keywords'  => ['...'],
  'content'   => [ /* paragraphs, lists, headings, images, iframes, videos (depending on parser) */ ],
  'createdAt' => 'YYYY-mm-dd HH:ii:ss' // UTC or null if not available
]
```

## Notes
- ArticleParserService fetches the page with file_get_contents(). Handle network timeouts and errors as needed in your application.
- Each portal has its own concrete parser. If the HTML markup of a source changes, a parser might require updates.

## Testing
Run unit tests with PHPUnit:

```bash
vendor/bin/phpunit
```

## License
MIT License


