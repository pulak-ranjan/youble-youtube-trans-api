<?php

namespace Youble\YouTubeTransApi\Exception;

use Exception;

/**
 * Exception thrown when a specific transcript is not found
 */
class TranscriptNotFound extends Exception
{
    public function __construct(string $videoId, array $requestedLanguages)
    {
        $languages = implode(', ', $requestedLanguages);
        parent::__construct(
            "No transcript found for video '{$videoId}' in languages: {$languages}"
        );
    }
}
