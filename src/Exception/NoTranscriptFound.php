<?php

namespace Youble\YouTubeTransApi\Exception;

use Exception;

/**
 * Exception thrown when no transcripts are available for a video
 */
class NoTranscriptFound extends Exception
{
    public function __construct(string $videoId)
    {
        parent::__construct(
            "No transcripts available for video '{$videoId}'"
        );
    }
}
