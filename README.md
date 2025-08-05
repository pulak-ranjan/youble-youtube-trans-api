Youble YouTube Transcript API
Fetch, translate and format YouTube captions in pure PHP – no official API key, no headless browser, no framework required.

✨ Features
Language cascade – pass ['de', 'en', 'fr'] and the first available caption track is returned.

Manual vs auto – prefers manually-created subtitles but can fall back to auto-generated ones.

Translation – YouTube’s own caption-translation endpoint (&tlang=) wrapped in one method call.

Multiple output formats – JSON, SRT, WebVTT, plain text (extensible via formatter interface).

Proxy ready – drop‐in Webshare or any HTTP/HTTPS/SOCKS proxy via PSR-7 Guzzle config.

Zero framework lock-in – works in Laravel, Symfony, CakePHP, FuelPHP or plain PHP.

Composer & PSR-4 – install with one command and autoload everything automatically.

CLI tool – fetch, translate or format captions right from the terminal.

🚀 Installation
bash
composer require youble/youtube-trans-api
Requires PHP 7.4+ and the ext-simplexml & ext-json extensions (both shipped by default).

⚡ Quick Start
php
require 'vendor/autoload.php';

use Youble\YouTubeTransApi\YouTubeTranscriptApi;
use Youble\YouTubeTransApi\Formatter\JsonFormatter;

$yt = new YouTubeTranscriptApi();

// Try German first, fall back to English
$snippets = $yt->fetch('dQw4w9WgXcQ', ['de', 'en']);

echo (new JsonFormatter())->format($snippets);
Translate to another language
php
use Youble\YouTubeTransApi\Formatter\SrtFormatter;

$list       = $yt->list('dQw4w9WgXcQ');
$enTrack    = $list->findTranscript(['en']);
$germanCopy = $enTrack->translate('de');      // auto-translated by YouTube
$srt        = (new SrtFormatter())->format($germanCopy->fetch());

file_put_contents('captions_de.srt', $srt);
🗄️ Folder Structure
text
src/
 ├── YouTubeTranscriptApi.php
 ├── Model/
 ├── Formatter/
 ├── Proxy/
 └── Exception/
tests/
examples/
bin/
Everything follows PSR-4 so IDEs and static analysers work out of the box.

🛠 CLI
bash
# Print German or English captions as JSON
vendor/bin/youtube-transcript dQw4w9WgXcQ --languages de en --format json

# Translate to French and save as .srt
vendor/bin/youtube-transcript dQw4w9WgXcQ --languages en --translate fr --format srt > captions_fr.srt
📜 API Reference (essentials)
Method	Description
fetch(string $videoId, array $languages = ['en'])	Returns caption snippets as PHP array.
list(string $videoId)	Returns TranscriptList with metadata for every track.
TranscriptList::findTranscript(array $langs)	Picks best track (manual > auto).
Transcript::fetch()	Downloads the caption XML and parses to array.
Transcript::translate(string $code)	Returns a new Transcript object auto-translated by YouTube.
🧩 Extending
Create your own formatter:

php
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
Pass it the snippet array returned by fetch() or Transcript::fetch().

🤝 Contributing
Fork the repo

composer install

Run composer test and make sure all PHPUnit cases pass

Submit your pull request

Issues and feature requests are welcome!

📄 License
Released under the MIT License – see LICENSE for details.

Built with ❤️ by Pulak Ranjan
