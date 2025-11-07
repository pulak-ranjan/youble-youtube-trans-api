<?php

namespace Youble\YouTubeTransApi\Formatter;

use Youble\YouTubeTransApi\Model\TranscriptSnippet;

/**
 * Interface for transcript formatters
 */
interface FormatterInterface
{
    /**
     * Format an array of transcript snippets
     *
     * @param TranscriptSnippet[] $snippets
     * @return string
     */
    public function format(array $snippets): string;
}
