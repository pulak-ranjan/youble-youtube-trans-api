<?php

namespace Youble\YouTubeTransApi\Model;

/**
 * Represents a single caption/subtitle snippet with text, start time and duration
 */
class TranscriptSnippet
{
    private string $text;
    private float $start;
    private float $duration;

    public function __construct(string $text, float $start, float $duration)
    {
        $this->text = $text;
        $this->start = $start;
        $this->duration = $duration;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getStart(): float
    {
        return $this->start;
    }

    public function getDuration(): float
    {
        return $this->duration;
    }

    public function getEnd(): float
    {
        return $this->start + $this->duration;
    }

    /**
     * Convert to array representation
     */
    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'start' => $this->start,
            'duration' => $this->duration,
        ];
    }
}
