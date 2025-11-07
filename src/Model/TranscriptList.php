<?php

namespace Youble\YouTubeTransApi\Model;

use Youble\YouTubeTransApi\Exception\TranscriptNotFound;

/**
 * Represents a collection of available transcripts for a video
 */
class TranscriptList
{
    private string $videoId;
    /** @var Transcript[] */
    private array $transcripts;
    /** @var Transcript[] */
    private array $manualTranscripts;
    /** @var Transcript[] */
    private array $generatedTranscripts;

    /**
     * @param Transcript[] $transcripts
     */
    public function __construct(string $videoId, array $transcripts)
    {
        $this->videoId = $videoId;
        $this->transcripts = $transcripts;

        $this->manualTranscripts = array_filter($transcripts, fn($t) => !$t->isGenerated());
        $this->generatedTranscripts = array_filter($transcripts, fn($t) => $t->isGenerated());
    }

    /**
     * @return Transcript[]
     */
    public function getAll(): array
    {
        return $this->transcripts;
    }

    /**
     * @return Transcript[]
     */
    public function getManualTranscripts(): array
    {
        return $this->manualTranscripts;
    }

    /**
     * @return Transcript[]
     */
    public function getGeneratedTranscripts(): array
    {
        return $this->generatedTranscripts;
    }

    /**
     * Find a transcript matching the requested languages (cascade)
     *
     * @param string[] $languageCodes Language codes in order of preference
     * @param bool $preferManual Whether to prefer manually created transcripts
     * @throws TranscriptNotFound
     */
    public function findTranscript(array $languageCodes, bool $preferManual = true): Transcript
    {
        // Try manual transcripts first if preferred
        if ($preferManual) {
            foreach ($languageCodes as $code) {
                foreach ($this->manualTranscripts as $transcript) {
                    if ($this->matchesLanguage($transcript->getLanguageCode(), $code)) {
                        return $transcript;
                    }
                }
            }
        }

        // Try all transcripts
        foreach ($languageCodes as $code) {
            foreach ($this->transcripts as $transcript) {
                if ($this->matchesLanguage($transcript->getLanguageCode(), $code)) {
                    return $transcript;
                }
            }
        }

        throw new TranscriptNotFound($this->videoId, $languageCodes);
    }

    /**
     * Check if language codes match (supports variants like en-US, en-GB)
     */
    private function matchesLanguage(string $available, string $requested): bool
    {
        $available = strtolower($available);
        $requested = strtolower($requested);

        // Exact match
        if ($available === $requested) {
            return true;
        }

        // Base language match (en-US matches en)
        $availableBase = explode('-', $available)[0];
        $requestedBase = explode('-', $requested)[0];

        return $availableBase === $requestedBase;
    }
}
