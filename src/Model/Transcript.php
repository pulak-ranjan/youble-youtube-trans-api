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
     *
     * @return TranscriptSnippet[]
     * @throws GuzzleException
     */
    public function fetch(): array
    {
        $client = $this->httpClient ?? new Client();

        $response = $client->get($this->url);
        $xml = new SimpleXMLElement($response->getBody()->getContents());

        $snippets = [];
        foreach ($xml->text as $text) {
            $snippets[] = new TranscriptSnippet(
                $this->decodeText((string)$text),
                (float)$text['start'],
                (float)$text['dur']
            );
        }

        return $snippets;
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
