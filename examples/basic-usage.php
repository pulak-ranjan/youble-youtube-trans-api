<?php

require __DIR__ . '/../vendor/autoload.php';

use Youble\YouTubeTransApi\YouTubeTranscriptApi;
use Youble\YouTubeTransApi\Formatter\JsonFormatter;
use Youble\YouTubeTransApi\Formatter\TextFormatter;

// Create API instance
$api = new YouTubeTranscriptApi();

// Fetch transcript in English
$videoId = 'dQw4w9WgXcQ'; // Example: Rick Astley - Never Gonna Give You Up

try {
    echo "Fetching transcript for video: {$videoId}\n\n";

    // Get transcript snippets
    $snippets = $api->fetch($videoId, ['en']);

    // Format as JSON
    echo "=== JSON Format ===\n";
    $jsonFormatter = new JsonFormatter();
    echo $jsonFormatter->format($snippets);
    echo "\n\n";

    // Format as plain text
    echo "=== Plain Text Format ===\n";
    $textFormatter = new TextFormatter();
    echo $textFormatter->format($snippets);
    echo "\n\n";

    echo "Total snippets: " . count($snippets) . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
