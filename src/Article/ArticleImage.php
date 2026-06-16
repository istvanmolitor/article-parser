<?php

namespace Molitor\ArticleParser\Article;

class ArticleImage
{
    protected string $src;

    protected ?string $alt;

    protected ?string $author;

    public function __construct(string $src)
    {
        $this->src = $src;
    }

    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAlt(?string $alt): void
    {
        $this->alt = $alt;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function getSrc(): string
    {
        return $this->src;
    }

    public function setSrc(string $src): void
    {
        $this->src = $src;
    }

    public function toArray(): array
    {
        return [
            'src' => $this->src,
            'alt' => $this->alt,
            'author' => $this->author,
        ];
    }

    public function toHtml(): string
    {
        $e = fn (string $s) => htmlspecialchars($s, ENT_QUOTES);

        $html = '<figure>';
        $html .= '<img src="'.$e($this->src).'" alt="'.$e($this->alt ?? '').'">';
        if (!empty($this->author)) {
            $html .= '<figcaption>'.$e($this->author).'</figcaption>';
        }
        $html .= '</figure>';

        return $html;
    }
}
