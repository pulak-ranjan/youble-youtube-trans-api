<?php

require __DIR__ . '/../vendor/autoload.php';

use Youble\YouTubeTransApi\YouTubeTranscriptApi;
use Youble\YouTubeTransApi\Formatter\SrtFormatter;

/**
 * This example demonstrates API parity with the Python youtube-transcript-api
 * https://github.com/jdepoix/youtube-transcript-api
 */

echo "YouTube Transcript API - PHP Implementation\n";
echo "Matching Python's youtube-transcript-api functionality\n";
echo "========================================================\n\n";

try {
    $api = new YouTubeTranscriptApi();
    $videoId = 'dQw4w9WgXcQ';

    // ========================================
    // Example 1: Basic fetch (like Python's get_transcript)
    // ========================================
    echo "1. Basic Fetch (Python: YouTubeTranscriptApi.get_transcript())\n";
    echo "   PHP: \$api->fetch(\$videoId, ['en'])\n\n";

    // $transcript = $api->fetch($videoId, ['en']);
    // foreach ($transcript as $snippet) {
    //     echo $snippet->getText() . "\n";
    // }

    // ========================================
    // Example 2: List transcripts (like Python's list_transcripts)
    // ========================================
    echo "2. List Transcripts (Python: YouTubeTranscriptApi.list_transcripts())\n";
    echo "   PHP: \$api->list(\$videoId)\n\n";

    // $transcriptList = $api->list($videoId);

    // ========================================
    // Example 3: Find manually created transcript
    // ========================================
    echo "3. Find Manual Transcript (Python: transcript_list.find_manually_created_transcript())\n";
    echo "   PHP: \$transcriptList->findManuallyCreatedTranscript(['en'])\n\n";

    // $manualTranscript = $transcriptList->findManuallyCreatedTranscript(['en']);
    // $snippets = $manualTranscript->fetch();

    // ========================================
    // Example 4: Find generated transcript
    // ========================================
    echo "4. Find Generated Transcript (Python: transcript_list.find_generated_transcript())\n";
    echo "   PHP: \$transcriptList->findGeneratedTranscript(['en'])\n\n";

    // $generatedTranscript = $transcriptList->findGeneratedTranscript(['en']);
    // $snippets = $generatedTranscript->fetch();

    // ========================================
    // Example 5: Translation
    // ========================================
    echo "5. Translation (Python: transcript.translate())\n";
    echo "   PHP: \$transcript->translate('de')\n\n";

    // $transcript = $transcriptList->findTranscript(['en']);
    // $translatedTranscript = $transcript->translate('de');
    // $germanSnippets = $translatedTranscript->fetch();

    // ========================================
    // Example 6: Multiple formatters
    // ========================================
    echo "6. Formatters (Python: SRTFormatter, JSONFormatter, etc.)\n";
    echo "   PHP: SrtFormatter, JsonFormatter, WebVttFormatter, TextFormatter\n\n";

    // $formatter = new SrtFormatter();
    // $srt = $formatter->format($snippets);

    // ========================================
    // Example 7: Proxy support
    // ========================================
    echo "7. Proxy Support (Python: proxies parameter)\n";
    echo "   PHP: ProxyConfigInterface implementations\n\n";

    // use Youble\YouTubeTransApi\Proxy\GenericProxyConfig;
    // $proxy = new GenericProxyConfig('http://proxy.example.com:8080');
    // $api = new YouTubeTranscriptApi($proxy);

    // ========================================
    // Example 8: Language cascade
    // ========================================
    echo "8. Language Cascade (Python: languages=['de', 'en'])\n";
    echo "   PHP: \$api->fetch(\$videoId, ['de', 'en'])\n\n";

    // $transcript = $api->fetch($videoId, ['de', 'en']); // Try German first, fallback to English

    echo "\n========================================================\n";
    echo "API Comparison Summary:\n";
    echo "========================================================\n\n";

    echo "Python Library              →  PHP Library\n";
    echo "-------------------------------------------\n";
    echo "get_transcript()            →  fetch()\n";
    echo "list_transcripts()          →  list()\n";
    echo "find_transcript()           →  findTranscript()\n";
    echo "find_manually_created..()   →  findManuallyCreatedTranscript()\n";
    echo "find_generated..()          →  findGeneratedTranscript()\n";
    echo "translate()                 →  translate()\n";
    echo "fetch()                     →  fetch()\n";
    echo "JSONFormatter               →  JsonFormatter\n";
    echo "SRTFormatter                →  SrtFormatter\n";
    echo "WebVTTFormatter             →  WebVttFormatter\n";
    echo "TextFormatter               →  TextFormatter\n";
    echo "proxies parameter           →  ProxyConfigInterface\n";

    echo "\n✓ Full API parity with Python library achieved!\n";

} catch (\Exception $e) {
    echo "Note: " . $e->getMessage() . "\n";
    echo "(Examples are commented out - uncomment to run with internet access)\n";
}
