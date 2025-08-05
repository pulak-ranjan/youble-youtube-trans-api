text
# Youble YouTube Transcript API

Fetch, translate and format YouTube captions in pure PHP—no official API key, no headless browser, no framework lock-in.

---

## ✨ Features

- **Language cascade** – pass `['de', 'en', 'fr']`; the first available track is returned.  
- **Manual vs auto** – prefers manually-created subtitles but falls back to auto-generated ones.  
- **Translation** – wraps YouTube’s built-in caption translation (`&tlang=`) in one method call.  
- **Multiple output formats** – JSON, SRT, WebVTT, plain text (extensible).  
- **Proxy ready** – drop-in Webshare or any HTTP/HTTPS/SOCKS proxy via Guzzle.  
- **Zero framework dependency** – works in Laravel, Symfony, CakePHP, FuelPHP or plain PHP.  
- **Composer & PSR-4** – one-line install, autoload everything automatically.  
- **CLI tool** – fetch, translate or format captions right from the terminal.

---

## 🚀 Installation

composer require youble/youtube-trans-api

text

Requires PHP 7.4 or higher with the default `simplexml` and `json` extensions.

---

## ⚡ Quick Start

require 'vendor/autoload.php';

use Youble\YouTubeTransApi\YouTubeTranscriptApi;
use Youble\YouTubeTransApi\Formatter\JsonFormatter;

$yt = new YouTubeTranscriptApi();

// Try German first, fall back to English
$snippets = $yt->fetch('dQw4w9WgXcQ', ['de', 'en']);

echo (new JsonFormatter())->format($snippets);

text

### Translate to another language

use Youble\YouTubeTransApi\Formatter\SrtFormatter;

$list = $yt->list('dQw4w9WgXcQ');
$enTrack = $list->findTranscript(['en']);
$germanCopy = $enTrack->translate('de'); // auto-translated by YouTube
$srt = (new SrtFormatter())->format($germanCopy->fetch());

file_put_contents('captions_de.srt', $srt);

text

---

## 🗄️ Folder Structure

src/
├── YouTubeTranscriptApi.php
├── Model/
├── Formatter/
├── Proxy/
└── Exception/
tests/
examples/
bin/

text

Everything follows PSR-4, so IDEs, static analysers and Composer autoloading work out-of-the-box.

---

## 🛠  CLI

Print German or English captions as JSON
vendor/bin/youtube-transcript dQw4w9WgXcQ --languages de en --format json

Translate to French and save as .srt
vendor/bin/youtube-transcript dQw4w9WgXcQ --languages en --translate fr --format srt > captions_fr.srt

text

---

## 📜 API Reference

| Method                                                        | Description                                                                    |
|--------------------------------------------------------------|--------------------------------------------------------------------------------|
| `fetch(string $videoId, array $languages = ['en'])`          | Returns caption snippets as a PHP array.                                       |
| `list(string $videoId)`                                      | Returns a `TranscriptList` with metadata for every available track.            |
| `TranscriptList::findTranscript(array $langs)`               | Picks the best track (manual > auto).                                          |
| `Transcript::fetch()`                                        | Downloads the caption XML and parses it to an array.                           |
| `Transcript::translate(string $targetCode)`                  | Returns a new `Transcript` object auto-translated by YouTube.                  |

---

## 🧩 Extending

Create a custom formatter:

use Youble\YouTubeTransApi\Formatter\FormatterInterface;

final class CsvFormatter implements FormatterInterface
{
public function format(array $rows): string
{
$csv = fopen('php://temp', 'r+');
foreach ($rows as $r) fputcsv($csv, $r);
rewind($csv);
return stream_get_contents($csv);
}
}

text

Pass it the snippet array returned by `fetch()` or `Transcript::fetch()`.

---

## 🤝 Contributing

1. Fork the repo  
2. `composer install`  
3. `composer test` (runs PHPUnit)  
4. Submit your pull request

Bug reports and feature requests are warmly welcomed!

---

## 📄 License

Released under the MIT License. See `LICENSE` for full details.

---

Built with ❤️ by **Pulak Ranjan**
