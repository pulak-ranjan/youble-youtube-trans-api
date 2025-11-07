<?php

namespace Youble\YouTubeTransApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Youble\YouTubeTransApi\Exception\NoTranscriptFound;
use Youble\YouTubeTransApi\Exception\VideoUnavailable;
use Youble\YouTubeTransApi\Model\Transcript;
use Youble\YouTubeTransApi\Model\TranscriptList;
use Youble\YouTubeTransApi\Model\TranscriptSnippet;
use Youble\YouTubeTransApi\Proxy\ProxyConfigInterface;

/**
 * Main API class for fetching YouTube transcripts
 */
class YouTubeTranscriptApi
{
    private Client $httpClient;

    public function __construct(?ProxyConfigInterface $proxyConfig = null)
    {
        $config = [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
            ],
            'verify' => true,
            'timeout' => 30,
            'allow_redirects' => true,
            'http_errors' => true,
        ];

        if ($proxyConfig) {
            $config = array_merge($config, $proxyConfig->getGuzzleConfig());
        }

        $this->httpClient = new Client($config);
    }

    /**
     * Fetch transcript snippets for a video
     *
     * @param string $videoId YouTube video ID
     * @param string[] $languageCodes Preferred language codes (cascade)
     * @param bool $preferManual Prefer manually created transcripts
     * @return TranscriptSnippet[]
     * @throws GuzzleException
     * @throws NoTranscriptFound
     * @throws VideoUnavailable
     */
    public function fetch(string $videoId, array $languageCodes = ['en'], bool $preferManual = true): array
    {
        $transcriptList = $this->list($videoId);
        $transcript = $transcriptList->findTranscript($languageCodes, $preferManual);
        return $transcript->fetch();
    }

    /**
     * List all available transcripts for a video
     *
     * @throws GuzzleException
     * @throws NoTranscriptFound
     * @throws VideoUnavailable
     */
    public function list(string $videoId): TranscriptList
    {
        $html = $this->fetchVideoPage($videoId);
        $transcripts = $this->extractTranscripts($videoId, $html);

        if (empty($transcripts)) {
            throw new NoTranscriptFound($videoId);
        }

        return new TranscriptList($videoId, $transcripts);
    }

    /**
     * Fetch the YouTube video page HTML
     *
     * @throws GuzzleException
     * @throws VideoUnavailable
     */
    private function fetchVideoPage(string $videoId): string
    {
        $url = "https://www.youtube.com/watch?v={$videoId}";

        try {
            $response = $this->httpClient->get($url);
            $html = $response->getBody()->getContents();

            // Check if video is unavailable
            if (strpos($html, 'This video is unavailable') !== false ||
                strpos($html, 'Video unavailable') !== false) {
                throw new VideoUnavailable($videoId);
            }

            return $html;
        } catch (GuzzleException $e) {
            throw new VideoUnavailable($videoId);
        }
    }

    /**
     * Extract transcript information from video page HTML
     *
     * @return Transcript[]
     */
    private function extractTranscripts(string $videoId, string $html): array
    {
        // Extract the ytInitialPlayerResponse JSON from the HTML
        $pattern = '/ytInitialPlayerResponse\s*=\s*({.+?});/';
        if (!preg_match($pattern, $html, $matches)) {
            return [];
        }

        $playerResponse = json_decode($matches[1], true);
        if (!$playerResponse) {
            return [];
        }

        // Navigate to captions data
        $captionTracks = $playerResponse['captions']['playerCaptionsTracklistRenderer']['captionTracks'] ?? [];
        $translationLanguages = $playerResponse['captions']['playerCaptionsTracklistRenderer']['translationLanguages'] ?? [];

        // Build translation languages map
        $translationMap = [];
        foreach ($translationLanguages as $lang) {
            $translationMap[$lang['languageCode']] = $lang['languageName']['simpleText'] ?? $lang['languageCode'];
        }

        $transcripts = [];
        foreach ($captionTracks as $track) {
            $languageCode = $track['languageCode'] ?? '';
            $baseUrl = $track['baseUrl'] ?? '';

            if (!$languageCode || !$baseUrl) {
                continue;
            }

            $languageName = $track['name']['simpleText'] ?? $languageCode;
            $isGenerated = isset($track['kind']) && $track['kind'] === 'asr';
            $isTranslatable = !empty($translationMap);

            $transcripts[] = new Transcript(
                $videoId,
                $baseUrl,
                $languageCode,
                $languageName,
                $isGenerated,
                $isTranslatable,
                $isTranslatable ? $translationMap : null,
                $this->httpClient
            );
        }

        return $transcripts;
    }

    /**
     * Set a custom HTTP client
     */
    public function setHttpClient(Client $client): void
    {
        $this->httpClient = $client;
    }

    /**
     * Get the current HTTP client
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }
}
