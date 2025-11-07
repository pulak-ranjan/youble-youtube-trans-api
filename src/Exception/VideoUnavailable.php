<?php

namespace Youble\YouTubeTransApi\Exception;

use Exception;

/**
 * Exception thrown when a video is unavailable or doesn't exist
 */
class VideoUnavailable extends Exception
{
    public function __construct(string $videoId)
    {
        parent::__construct(
            "Video '{$videoId}' is unavailable or does not exist"
        );
    }
}
