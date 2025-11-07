<?php

namespace Youble\YouTubeTransApi\Proxy;

/**
 * Webshare proxy configuration
 */
class WebshareProxyConfig implements ProxyConfigInterface
{
    private string $host;
    private int $port;
    private string $username;
    private string $password;

    /**
     * @param string $host Webshare proxy host
     * @param int $port Webshare proxy port (default: 80)
     * @param string $username Webshare username
     * @param string $password Webshare password
     */
    public function __construct(string $host, int $port, string $username, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    public function getGuzzleConfig(): array
    {
        $proxy = sprintf(
            'http://%s:%s@%s:%d',
            urlencode($this->username),
            urlencode($this->password),
            $this->host,
            $this->port
        );

        return [
            'proxy' => $proxy,
        ];
    }
}
