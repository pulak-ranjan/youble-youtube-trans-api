<?php

namespace Youble\YouTubeTransApi\Formatter;

use Youble\YouTubeTransApi\Model\TranscriptSnippet;

/**
 * Format transcript as JSON
 */
class JsonFormatter implements FormatterInterface
{
    private bool $prettyPrint;

    public function __construct(bool $prettyPrint = true)
    {
        $this->prettyPrint = $prettyPrint;
    }

    /**
     * @param TranscriptSnippet[] $snippets
     */
    public function format(array $snippets): string
    {
        $data = array_map(fn($s) => $s->toArray(), $snippets);

        $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        if ($this->prettyPrint) {
            $flags |= JSON_PRETTY_PRINT;
        }

        return json_encode($data, $flags);
    }
}
