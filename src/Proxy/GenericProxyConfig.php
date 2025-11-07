<?php

namespace Youble\YouTubeTransApi\Proxy;

/**
 * Generic proxy configuration supporting HTTP, HTTPS, and SOCKS proxies
 */
class GenericProxyConfig implements ProxyConfigInterface
{
    private string $proxyUrl;
    private ?string $username;
    private ?string $password;

    /**
     * @param string $proxyUrl Proxy URL (e.g., 'http://proxy.example.com:8080', 'socks5://127.0.0.1:9050')
     * @param string|null $username Optional proxy username
     * @param string|null $password Optional proxy password
     */
    public function __construct(string $proxyUrl, ?string $username = null, ?string $password = null)
    {
        $this->proxyUrl = $proxyUrl;
        $this->username = $username;
        $this->password = $password;
    }

    public function getGuzzleConfig(): array
    {
        $proxy = $this->proxyUrl;

        // Add authentication if provided
        if ($this->username && $this->password) {
            $parsed = parse_url($this->proxyUrl);
            $scheme = $parsed['scheme'] ?? 'http';
            $host = $parsed['host'] ?? '';
            $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';

            $proxy = sprintf(
                '%s://%s:%s@%s%s',
                $scheme,
                urlencode($this->username),
                urlencode($this->password),
                $host,
                $port
            );
        }

        return [
            'proxy' => $proxy,
        ];
    }
}
