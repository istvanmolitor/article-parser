<?php

namespace Molitor\ArticleParser\Article;

class VideoArticleContentElement extends ArticleContentElement
{
    protected string $src;

    protected string $type;

    protected null|string $title = null;

    public function __construct(string $src) {
        $this->src = $src;
        $this->type = $this->getTypeBySrc($src);
    }

    protected function getTypeBySrc(string $src)
    {
        $src = trim($src);
        if ($src === '') {
            return 'unknown';
        }

        $host = '';
        $ext  = '';

        $parts = @parse_url($src);
        if (is_array($parts)) {
            $host = isset($parts['host']) ? strtolower($parts['host']) : '';
            $host = preg_replace('/^www\./', '', $host);
            $path = $parts['path'] ?? '';

            if (is_string($path)) {
                $basename = basename($path);
                $dotPos = strrpos($basename, '.');
                if ($dotPos !== false) {
                    $ext = strtolower(substr($basename, $dotPos + 1));
                }
            }
        }

        if ($host !== '') {
            // YouTube
            if ($host === 'youtube.com' || $host === 'youtu.be' || $host === 'm.youtube.com') {
                return 'youtube';
            }
            // Vimeo
            if ($host === 'vimeo.com' || $host === 'player.vimeo.com') {
                return 'vimeo';
            }
            // Dailymotion
            if ($host === 'dailymotion.com' || $host === 'www.dailymotion.com' || $host === 'dai.ly') {
                return 'dailymotion';
            }
            // Facebook
            if ($host === 'facebook.com' || $host === 'fb.watch' || $host === 'm.facebook.com') {
                return 'facebook';
            }
            // Twitch
            if ($host === 'twitch.tv' || $host === 'clips.twitch.tv' || $host === 'player.twitch.tv') {
                return 'twitch';
            }
            // TikTok
            if ($host === 'tiktok.com' || preg_match('/(^|\.)tiktok\.com$/', $host)) {
                return 'tiktok';
            }
            // Streamable
            if ($host === 'streamable.com') {
                return 'streamable';
            }
            // Wistia
            if ($host === 'wistia.com' || $host === 'fast.wistia.com') {
                return 'wistia';
            }
            // Brightcove (általános host)
            if ($host === 'players.brightcove.net') {
                return 'brightcove';
            }
        }

        if (in_array($ext, ['mp4', 'webm', 'ogv', 'ogg'], true)) {
            return 'html5';
        }
        if ($ext === 'm3u8') {
            return 'hls';
        }
        if ($ext === 'mpd') {
            return 'dash';
        }

        $lowerSrc = strtolower($src);
        if (strpos($lowerSrc, 'youtube.com') !== false || strpos($lowerSrc, 'youtu.be') !== false) {
            return 'youtube';
        }
        if (strpos($lowerSrc, 'vimeo.com') !== false) {
            return 'vimeo';
        }
        if (strpos($lowerSrc, 'dailymotion.com') !== false || strpos($lowerSrc, 'dai.ly') !== false) {
            return 'dailymotion';
        }
        if (strpos($lowerSrc, 'facebook.com') !== false || strpos($lowerSrc, 'fb.watch') !== false) {
            return 'facebook';
        }
        if (strpos($lowerSrc, 'twitch.tv') !== false) {
            return 'twitch';
        }
        if (preg_match('/\.(mp4|webm|ogv|ogg)(\?|#|$)/', $lowerSrc)) {
            return 'html5';
        }
        if (preg_match('/\.m3u8(\?|#|$)/', $lowerSrc)) {
            return 'hls';
        }
        if (preg_match('/\.mpd(\?|#|$)/', $lowerSrc)) {
            return 'dash';
        }

        return 'unknown';
    }

    public function getData(): array
    {
        return [
            'src' => $this->src,
            'type' => $this->type,
            'title' => $this->title,
        ];
    }

    /**
     * @param string $src
     */
    public function setSrc(string $src): void
    {
        $this->src = $src;
    }

    /**
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function __toString(): string
    {
        return (string)$this->title;
    }
}
