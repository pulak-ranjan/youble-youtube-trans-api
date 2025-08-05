<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Youble YouTube Transcript API</title>
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
      max-width: 800px;
      margin: 2rem auto;
      padding: 0 1rem;
      line-height: 1.6;
      color: #24292e;
    }
    h1, h2, h3 {
      font-weight: 600;
    }
    hr {
      border: none;
      border-top: 1px solid #e1e4e8;
      margin: 2rem 0;
    }
    ul {
      list-style: none;
      padding: 0;
    }
    ul > li {
      margin-bottom: 0.5rem;
    }
    pre, code {
      background-color: #f6f8fa;
      border-radius: 3px;
    }
    pre {
      padding: 1rem;
      overflow-x: auto;
    }
    code {
      padding: .2rem .4rem;
      font-size: 85%;
    }
    a {
      color: #0366d6;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    footer {
      margin-top: 3rem;
      font-size: 0.9rem;
      text-align: center;
      color: #57606a;
    }
  </style>
</head>
<body>
  <h1>Youble YouTube Transcript API</h1>
  <p>A lightweight PHP library to fetch, translate, and format YouTube captions—no API key, no headless browser, zero framework lock-in.</p>
  <p>
    <a href="https://packagist.org/packages/youble/youtube-trans-api" target="_blank">Packagist ↗</a>
    &nbsp;•&nbsp;
    <a href="https://github.com/pulak-ranjan/youtube-trans-api" target="_blank">Source ↗</a>
  </p>
  <hr>

  <h2>✨ Features</h2>
  <ul>
    <li>🔠 <strong>Language Cascade</strong><br>
      Pass <code>['de','en','fr']</code>; returns the first available track.
    </li>
    <li>📝 <strong>Manual vs Auto</strong><br>
      Prefers human-created subtitles, falls back to auto-generated.
    </li>
    <li>🌐 <strong>Translation</strong><br>
      One-call wrapper for YouTube’s built-in <code>&amp;tlang=</code> translation.
    </li>
    <li>🗂 <strong>Multiple Formats</strong><br>
      JSON, SRT, WebVTT, plain text (extensible).
    </li>
    <li>🛡 <strong>Proxy Ready</strong><br>
      Works with HTTP/HTTPS/SOCKS proxies via Guzzle.
    </li>
    <li>⚙️ <strong>Zero Framework Dependency</strong><br>
      Compatible with Laravel, Symfony, CakePHP, FuelPHP, or plain PHP.
    </li>
    <li>📦 <strong>Composer &amp; PSR-4</strong><br>
      One-line install, automatic autoloading.
    </li>
    <li>💻 <strong>CLI Tool</strong><br>
      Fetch, translate, or format captions from the terminal.
    </li>
  </ul>

  <hr>
  <h2>📥 Installation</h2>
  <ol>
    <li><strong>Require via Composer</strong><br>
      <pre><code>composer require youble/youtube-trans-api</code></pre>
    </li>
    <li><strong>Ensure</strong> PHP 7.4+ with the <code>simplexml</code> and <code>json</code> extensions.</li>
  </ol>

  <hr>
  <h2>🚀 Usage</h2>
  <pre><code>require 'vendor/autoload.php';

use Youble\YouTubeTransApi\YouTubeTranscriptApi;
use Youble\YouTubeTransApi\Formatter\JsonFormatter;

$yt = new YouTubeTranscriptApi();

// Fetch German first, fallback to English
$snippets = $yt->fetch('dQw4w9WgXcQ', ['de', 'en']);
echo (new JsonFormatter())->format($snippets);
</code></pre>

  <h3>Translate to Another Language</h3>
  <pre><code>use Youble\YouTubeTransApi\Formatter\SrtFormatter;

$list       = $yt->list('dQw4w9WgXcQ');
$enTrack    = $list->findTranscript(['en']);
$germanCopy = $enTrack->translate('de'); // Auto-translated
$srt        = (new SrtFormatter())->format($germanCopy->fetch());
file_put_contents('captions_de.srt', $srt);
</code></pre>

  <hr>
  <h2>🛠 CLI</h2>
  <ul>
    <li><strong>Generate JSON captions</strong> (German or English):<br>
      <pre><code>vendor/bin/youtube-transcript dQw4w9WgXcQ --languages de en --format json</code></pre>
    </li>
    <li><strong>Translate to French &amp; save as SRT</strong>:<br>
      <pre><code>vendor/bin/youtube-transcript dQw4w9WgXcQ --languages en --translate fr --format srt &gt; captions_fr.srt</code></pre>
    </li>
  </ul>

  <hr>
  <h2>📜 API Reference</h2>
  <ul>
    <li><code>fetch(string $videoId, array $languages = ['en'])</code><br>
      Returns an array of caption snippets.
    </li>
    <li><code>list(string $videoId)</code><br>
      Returns a <code>TranscriptList</code> object with metadata for all tracks.
    </li>
    <li><code>TranscriptList::findTranscript(array $langs)</code><br>
      Selects the best track (manual &gt; auto).
    </li>
    <li><code>Transcript::fetch()</code><br>
      Downloads and parses the XML captions.
    </li>
    <li><code>Transcript::translate(string $targetCode)</code><br>
      Returns a new <code>Transcript</code> object auto-translated by YouTube.
    </li>
  </ul>

  <hr>
  <h2>🧩 Extending</h2>
  <pre><code>use Youble\YouTubeTransApi\Formatter\FormatterInterface;

final class CsvFormatter implements FormatterInterface
{
    public function format(array $rows): string
    {
        $csv = fopen('php://temp', 'r+');
        foreach ($rows as $r) {
            fputcsv($csv, $r);
        }
        rewind($csv);
        return stream_get_contents($csv);
    }
}
</code></pre>
  <p>Pass your formatter the array from <code>fetch()</code> or <code>Transcript::fetch()</code>.</p>

  <hr>
  <h2>🤝 Contributing</h2>
  <ol>
    <li>Fork the repo</li>
    <li><code>composer install</code></li>
    <li><code>composer test</code> (via PHPUnit)</li>
    <li>Submit a PR</li>
  </ol>
  <p>Bug reports and feature requests are welcome!</p>

  <footer>
    Made with ❤️ by <a href="https://github.com/pulak-ranjan" target="_blank">Pulak Ranjan</a>
  </footer>
</body>
</html>
