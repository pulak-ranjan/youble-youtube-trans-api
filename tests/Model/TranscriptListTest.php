<?php

namespace Youble\YouTubeTransApi\Tests\Model;

use PHPUnit\Framework\TestCase;
use Youble\YouTubeTransApi\Model\Transcript;
use Youble\YouTubeTransApi\Model\TranscriptList;
use Youble\YouTubeTransApi\Exception\TranscriptNotFound;

class TranscriptListTest extends TestCase
{
    private TranscriptList $list;

    protected function setUp(): void
    {
        $transcripts = [
            new Transcript('test', 'http://example.com/en', 'en', 'English', false, true),
            new Transcript('test', 'http://example.com/de', 'de', 'German', false, false),
            new Transcript('test', 'http://example.com/en-auto', 'en', 'English (auto)', true, false),
            new Transcript('test', 'http://example.com/fr-auto', 'fr', 'French (auto)', true, false),
        ];

        $this->list = new TranscriptList('test', $transcripts);
    }

    public function testGetAll(): void
    {
        $all = $this->list->getAll();
        $this->assertCount(4, $all);
    }

    public function testGetManualTranscripts(): void
    {
        $manual = $this->list->getManualTranscripts();
        $this->assertCount(2, $manual);

        foreach ($manual as $transcript) {
            $this->assertFalse($transcript->isGenerated());
        }
    }

    public function testGetGeneratedTranscripts(): void
    {
        $generated = $this->list->getGeneratedTranscripts();
        $this->assertCount(2, $generated);

        foreach ($generated as $transcript) {
            $this->assertTrue($transcript->isGenerated());
        }
    }

    public function testFindTranscript(): void
    {
        $transcript = $this->list->findTranscript(['en']);
        $this->assertEquals('en', $transcript->getLanguageCode());
        $this->assertFalse($transcript->isGenerated()); // Should prefer manual
    }

    public function testFindTranscriptWithoutPreference(): void
    {
        $transcript = $this->list->findTranscript(['en'], false);
        $this->assertEquals('en', $transcript->getLanguageCode());
    }

    public function testFindManuallyCreatedTranscript(): void
    {
        $transcript = $this->list->findManuallyCreatedTranscript(['en']);
        $this->assertEquals('en', $transcript->getLanguageCode());
        $this->assertFalse($transcript->isGenerated());
    }

    public function testFindManuallyCreatedTranscriptNotFound(): void
    {
        $this->expectException(TranscriptNotFound::class);
        $this->list->findManuallyCreatedTranscript(['fr']); // Only auto-generated French available
    }

    public function testFindGeneratedTranscript(): void
    {
        $transcript = $this->list->findGeneratedTranscript(['en']);
        $this->assertEquals('en', $transcript->getLanguageCode());
        $this->assertTrue($transcript->isGenerated());
    }

    public function testFindGeneratedTranscriptNotFound(): void
    {
        $this->expectException(TranscriptNotFound::class);
        $this->list->findGeneratedTranscript(['de']); // Only manual German available
    }

    public function testLanguageCascade(): void
    {
        $transcript = $this->list->findTranscript(['es', 'fr', 'en']);
        $this->assertEquals('en', $transcript->getLanguageCode()); // Falls back to 'en'
    }

    public function testLanguageCascadeNotFound(): void
    {
        $this->expectException(TranscriptNotFound::class);
        $this->list->findTranscript(['es', 'it', 'pt']);
    }
}
