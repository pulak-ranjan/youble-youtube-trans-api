<?php

namespace Youble\YouTubeTransApi\Formatter;

use Youble\YouTubeTransApi\Model\TranscriptSnippet;

/**
 * Format transcript as SRT (SubRip) subtitles
 */
class SrtFormatter implements FormatterInterface
{
    /**
     * @param TranscriptSnippet[] $snippets
     */
    public function format(array $snippets): string
    {
        $srt = '';
        $index = 1;

        foreach ($snippets as $snippet) {
            $srt .= $index . "\n";
            $srt .= $this->formatTime($snippet->getStart()) . ' --> ' . $this->formatTime($snippet->getEnd()) . "\n";
            $srt .= $snippet->getText() . "\n\n";
            $index++;
        }

        return rtrim($srt);
    }

    /**
     * Format time in SRT format (HH:MM:SS,mmm)
     */
    private function formatTime(float $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = floor($seconds % 60);
        $millis = round(($seconds - floor($seconds)) * 1000);

        return sprintf('%02d:%02d:%02d,%03d', $hours, $minutes, $secs, $millis);
    }
}
