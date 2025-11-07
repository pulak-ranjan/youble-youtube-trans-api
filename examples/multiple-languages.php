<?php

require __DIR__ . '/../vendor/autoload.php';

use Youble\YouTubeTransApi\YouTubeTranscriptApi;
use Youble\YouTubeTransApi\Formatter\TextFormatter;

// Create API instance
$api = new YouTubeTranscriptApi();

$videoId = 'dQw4w9WgXcQ';

try {
    echo "Listing available transcripts for video: {$videoId}\n\n";

    // List all available transcripts
    $transcriptList = $api->list($videoId);

    echo "=== Manual Transcripts ===\n";
    foreach ($transcriptList->getManualTranscripts() as $transcript) {
        echo "- {$transcript->getLanguageName()} ({$transcript->getLanguageCode()})\n";
    }

    echo "\n=== Auto-Generated Transcripts ===\n";
    foreach ($transcriptList->getGeneratedTranscripts() as $transcript) {
        echo "- {$transcript->getLanguageName()} ({$transcript->getLanguageCode()})\n";
    }

    // Fetch with language cascade (try German first, fallback to English)
    echo "\n=== Fetching with Language Cascade (de -> en) ===\n";
    $snippets = $api->fetch($videoId, ['de', 'en']);

    $textFormatter = new TextFormatter();
    $text = $textFormatter->format($snippets);

    echo substr($text, 0, 500) . "...\n"; // First 500 chars

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
