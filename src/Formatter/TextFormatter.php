<?php

namespace Youble\YouTubeTransApi\Formatter;

use Youble\YouTubeTransApi\Model\TranscriptSnippet;

/**
 * Format transcript as plain text
 */
class TextFormatter implements FormatterInterface
{
    private string $separator;

    public function __construct(string $separator = "\n")
    {
        $this->separator = $separator;
    }

    /**
     * @param TranscriptSnippet[] $snippets
     */
    public function format(array $snippets): string
    {
        $texts = array_map(fn($s) => $s->getText(), $snippets);
        return implode($this->separator, $texts);
    }
}
