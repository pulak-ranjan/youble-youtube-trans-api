<?php

namespace Youble\YouTubeTransApi\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use Youble\YouTubeTransApi\Formatter\SrtFormatter;
use Youble\YouTubeTransApi\Model\TranscriptSnippet;

class SrtFormatterTest extends TestCase
{
    public function testFormatsSnippetsAsSrt(): void
    {
        $snippets = [
            new TranscriptSnippet('Hello world', 0.0, 2.5),
            new TranscriptSnippet('This is a test', 2.5, 3.0),
        ];

        $formatter = new SrtFormatter();
        $result = $formatter->format($snippets);

        // Check SRT format structure
        $this->assertStringContainsString('1', $result);
        $this->assertStringContainsString('00:00:00,000 --> 00:00:02,500', $result);
        $this->assertStringContainsString('Hello world', $result);

        $this->assertStringContainsString('2', $result);
        $this->assertStringContainsString('00:00:02,500 --> 00:00:05,500', $result);
        $this->assertStringContainsString('This is a test', $result);
    }

    public function testFormatTimeCorrectly(): void
    {
        $snippets = [
            new TranscriptSnippet('Test', 3661.5, 1.0), // 1 hour, 1 minute, 1.5 seconds
        ];

        $formatter = new SrtFormatter();
        $result = $formatter->format($snippets);

        $this->assertStringContainsString('01:01:01,500', $result);
    }
}
