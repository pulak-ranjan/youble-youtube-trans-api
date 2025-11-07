<?php

namespace Youble\YouTubeTransApi\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use SimpleXMLElement;

/**
 * Represents a single transcript track with language and translation capabilities
 */
class Transcript
{
    private string $videoId;
    private string $url;
    private string $languageCode;
    private string $languageName;
    private bool $isGenerated;
    private bool $isTranslatable;
    private ?array $translationLanguages;
    private ?Client $httpClient;
    private ?array $cachedSnippets = null;

    public function __construct(
        string $videoId,
        string $url,
        string $languageCode,
        string $languageName,
        bool $isGenerated = false,
        bool $isTranslatable = false,
        ?array $translationLanguages = null,
        ?Client $httpClient = null
    ) {
        $this->videoId = $videoId;
        $this->url = $url;
        $this->languageCode = $languageCode;
        $this->languageName = $languageName;
        $this->isGenerated = $isGenerated;
        $this->isTranslatable = $isTranslatable;
        $this->translationLanguages = $translationLanguages;
        $this->httpClient = $httpClient;

        // Fetch immediately to avoid URL expiration
        // YouTube transcript URLs expire within seconds
        $this->cachedSnippets = $this->fetchFromUrl();
    }

    public function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    public function getLanguageName(): string
    {
        return $this->languageName;
    }

    public function isGenerated(): bool
    {
        return $this->isGenerated;
    }

    public function isTranslatable(): bool
    {
        return $this->isTranslatable;
    }

    public function getTranslationLanguages(): ?array
    {
        return $this->translationLanguages;
    }

    /**
     * Fetch the transcript snippets
     * Returns cached data since URLs expire quickly
     *
     * @return TranscriptSnippet[]
     */
    public function fetch(): array
    {
        return $this->cachedSnippets ?? [];
    }

    /**
     * Fetch transcript data from YouTube URL
     * Called immediately in constructor to avoid URL expiration
     *
     * @return TranscriptSnippet[]
     */
    private function fetchFromUrl(): array
    {
        $client = $this->httpClient ?? new Client();

        $response = $client->get($this->url);
        $xmlContent = $response->getBody()->getContents();

        // Clean and prepare XML content
        $xmlContent = $this->cleanXmlContent($xmlContent);

        // Suppress XML parsing errors and handle them gracefully
        libxml_use_internal_errors(true);

        $xml = null;
        try {
            // Try to parse XML with options for handling malformed content
            $xml = simplexml_load_string(
                $xmlContent,
                'SimpleXMLElement',
                LIBXML_NOCDATA | LIBXML_NOWARNING | LIBXML_NOERROR
            );
        } catch (\Exception $e) {
            // Ignore and try alternative methods
        }

        // If simplexml_load_string failed, try more aggressive cleaning
        if ($xml === false || $xml === null) {
            // Remove control characters except newlines and tabs
            $xmlContent = preg_replace('/[\x00-\x08\x0B-\x0C\x0E-\x1F\x7F]/u', '', $xmlContent);

            // Remove any invalid UTF-8 characters
            $xmlContent = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $xmlContent);

            try {
                $xml = simplexml_load_string(
                    $xmlContent,
                    'SimpleXMLElement',
                    LIBXML_NOCDATA | LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_COMPACT | LIBXML_PARSEHUGE
                );
            } catch (\Exception $e) {
                // Last resort - return empty array
                libxml_clear_errors();
                return [];
            }
        }

        libxml_clear_errors();

        if (!$xml) {
            return [];
        }

        $snippets = [];
        foreach ($xml->text as $text) {
            $start = isset($text['start']) ? (float)$text['start'] : 0.0;
            $dur = isset($text['dur']) ? (float)$text['dur'] : 0.0;

            $snippets[] = new TranscriptSnippet(
                $this->decodeText((string)$text),
                $start,
                $dur
            );
        }

        return $snippets;
    }

    /**
     * Clean XML content to handle malformed responses
     */
    private function cleanXmlContent(string $content): string
    {
        // Decode HTML entities first
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove BOM and other invisible characters
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

        // Ensure proper UTF-8 encoding
        if (!mb_check_encoding($content, 'UTF-8')) {
            $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
        }

        return trim($content);
    }

    /**
     * Translate this transcript to another language
     */
    public function translate(string $languageCode): self
    {
        $translatedUrl = $this->url . '&tlang=' . $languageCode;

        $targetLanguageName = $languageCode;
        if ($this->translationLanguages && isset($this->translationLanguages[$languageCode])) {
            $targetLanguageName = $this->translationLanguages[$languageCode];
        }

        return new self(
            $this->videoId,
            $translatedUrl,
            $languageCode,
            $targetLanguageName,
            true, // translated transcripts are considered generated
            false,
            null,
            $this->httpClient
        );
    }

    /**
     * Decode HTML entities in text
     */
    private function decodeText(string $text): string
    {
        return html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
