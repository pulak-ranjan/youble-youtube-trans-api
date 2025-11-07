<?php

require __DIR__ . '/../vendor/autoload.php';

use Youble\YouTubeTransApi\YouTubeTranscriptApi;
use Youble\YouTubeTransApi\Formatter\SrtFormatter;
use Youble\YouTubeTransApi\Formatter\WebVttFormatter;
use Youble\YouTubeTransApi\Formatter\JsonFormatter;

// Create API instance
$api = new YouTubeTranscriptApi();

$videoId = 'dQw4w9WgXcQ';

try {
    echo "Fetching and formatting transcript in multiple formats\n\n";

    // Fetch English transcript
    $snippets = $api->fetch($videoId, ['en']);

    // SRT Format
    echo "=== SRT Format (SubRip) ===\n";
    $srtFormatter = new SrtFormatter();
    $srt = $srtFormatter->format($snippets);
    echo substr($srt, 0, 300) . "\n...\n\n";

    // Save to file
    file_put_contents(__DIR__ . '/output.srt', $srt);
    echo "Saved to: output.srt\n\n";

    // WebVTT Format
    echo "=== WebVTT Format ===\n";
    $vttFormatter = new WebVttFormatter();
    $vtt = $vttFormatter->format($snippets);
    echo substr($vtt, 0, 300) . "\n...\n\n";

    // Save to file
    file_put_contents(__DIR__ . '/output.vtt', $vtt);
    echo "Saved to: output.vtt\n\n";

    // JSON Format (compact)
    echo "=== JSON Format (Compact) ===\n";
    $jsonFormatter = new JsonFormatter(false);
    $json = $jsonFormatter->format(array_slice($snippets, 0, 3)); // First 3 snippets
    echo $json . "\n\n";

    // Translation example
    echo "=== Translation Example (English to German) ===\n";
    $list = $api->list($videoId);
    $enTranscript = $list->findTranscript(['en']);
    $deTranscript = $enTranscript->translate('de');
    $deSnippets = $deTranscript->fetch();

    echo "First 3 German snippets:\n";
    foreach (array_slice($deSnippets, 0, 3) as $snippet) {
        echo "- " . $snippet->getText() . "\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
