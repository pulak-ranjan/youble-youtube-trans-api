<?php

namespace Youble\YouTubeTransApi\Formatter;

use Youble\YouTubeTransApi\Model\TranscriptSnippet;

/**
 * Format transcript as WebVTT subtitles
 */
class WebVttFormatter implements FormatterInterface
{
    /**
     * @param TranscriptSnippet[] $snippets
     */
    public function format(array $snippets): string
    {
        $vtt = "WEBVTT\n\n";

        foreach ($snippets as $snippet) {
            $vtt .= $this->formatTime($snippet->getStart()) . ' --> ' . $this->formatTime($snippet->getEnd()) . "\n";
            $vtt .= $snippet->getText() . "\n\n";
        }

        return rtrim($vtt);
    }

    /**
     * Format time in WebVTT format (HH:MM:SS.mmm)
     */
    private function formatTime(float $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = floor($seconds % 60);
        $millis = round(($seconds - floor($seconds)) * 1000);

        return sprintf('%02d:%02d:%02d.%03d', $hours, $minutes, $secs, $millis);
    }
}
