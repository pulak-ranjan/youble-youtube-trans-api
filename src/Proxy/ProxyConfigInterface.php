<?php

namespace Youble\YouTubeTransApi\Proxy;

/**
 * Interface for proxy configurations
 */
interface ProxyConfigInterface
{
    /**
     * Get Guzzle HTTP client configuration array
     *
     * @return array
     */
    public function getGuzzleConfig(): array;
}
