<?php

namespace Youble\YouTubeTransApi\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use Youble\YouTubeTransApi\Formatter\JsonFormatter;
use Youble\YouTubeTransApi\Model\TranscriptSnippet;

class JsonFormatterTest extends TestCase
{
    public function testFormatsSnippetsAsJson(): void
    {
        $snippets = [
            new TranscriptSnippet('Hello world', 0.0, 2.5),
            new TranscriptSnippet('This is a test', 2.5, 3.0),
        ];

        $formatter = new JsonFormatter(false); // No pretty print for easier testing
        $result = $formatter->format($snippets);

        $this->assertJson($result);

        $decoded = json_decode($result, true);
        $this->assertCount(2, $decoded);

        $this->assertEquals('Hello world', $decoded[0]['text']);
        $this->assertEquals(0.0, $decoded[0]['start']);
        $this->assertEquals(2.5, $decoded[0]['duration']);
    }

    public function testPrettyPrintOption(): void
    {
        $snippets = [
            new TranscriptSnippet('Test', 0.0, 1.0),
        ];

        $formatter = new JsonFormatter(true);
        $result = $formatter->format($snippets);

        // Pretty printed JSON should contain newlines
        $this->assertStringContainsString("\n", $result);
    }
}
