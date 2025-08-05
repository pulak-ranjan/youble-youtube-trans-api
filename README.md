# Youble YouTube Transcript API

**Fetch, translate and format YouTube captions in pure PHP – no official API key, no headless browser, no framework required.**

[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Latest Version](https://img.shields.io/packagist/v/youble/youtube-trans-api.svg)](https://packagist.org/packages/youble/youtube-trans-api)
[![Total Downloads](https://img.shields.io/packagist/dt/youble/youtube-trans-api.svg)](https://packagist.org/packages/youble/youtube-trans-api)

---

## ✨ Features

- **Language cascade** – pass `['de', 'en', 'fr']` and the first available caption track is returned
- **Manual vs auto** – prefers manually-created subtitles but can fall back to auto-generated ones
- **Translation** – YouTube's own caption-translation endpoint (`&tlang=`) wrapped in one method call
- **Multiple output formats** – JSON, SRT, WebVTT, plain text (extensible via formatter interface)
- **Proxy ready** – drop-in Webshare or any HTTP/HTTPS/SOCKS proxy via PSR-7 Guzzle config
- **Zero framework lock-in** – works in Laravel, Symfony, CakePHP, FuelPHP or plain PHP
- **Composer & PSR-4** – install with one command and autoload everything automatically
- **CLI tool** – fetch, translate or format captions right from the terminal

---

## 🚀 Installation
```bash
composer require youble/youtube-trans-api
```

**Requirements:**
- PHP 7.4+
- `ext-simplexml` (shipped by default)
- `ext-json` (shipped by default)
```text
composer require youble/youtube-trans-api
```

---


## 🗂️ Folder Structure

```text
youble-youtube-trans-api/
├── src/
│   ├── YouTubeTranscriptApi.php
│   ├── Exception/
│   │   ├── TranscriptNotFound.php
│   │   ├── NoTranscriptFound.php
│   │   └── VideoUnavailable.php
│   ├── Formatter/
│   │   ├── FormatterInterface.php
│   │   ├── JsonFormatter.php
│   │   ├── SrtFormatter.php
│   │   ├── TextFormatter.php
│   │   └── WebVttFormatter.php
│   ├── Model/
│   │   ├── Transcript.php
│   │   ├── TranscriptList.php
│   │   └── TranscriptSnippet.php
│   └── Proxy/
│       ├── ProxyConfigInterface.php
│       ├── WebshareProxyConfig.php
│       └── GenericProxyConfig.php
├── tests/
│   ├── YouTubeTranscriptApiTest.php
│   └── Formatter/
│       ├── JsonFormatterTest.php
│       └── SrtFormatterTest.php
├── examples/
│   ├── basic-usage.php
│   ├── multiple-languages.php
│   └── format-examples.php
├── bin/
│   └── youtube-transcript
├── composer.json
├── phpunit.xml
├── README.md
├── LICENSE
└── .gitignore
```
## 🚀 Quick Start
```text
require 'vendor/autoload.php';

use Youble\YouTubeTransApi\YouTubeTranscriptApi;
use Youble\YouTubeTransApi\Formatter\JsonFormatter;

$yt = new YouTubeTranscriptApi();

// Fetch German first, fallback to English
$snippets = $yt->fetch('dQw4w9WgXcQ', ['de', 'en']);
echo (new JsonFormatter())->format($snippets);
```

**Translate to Another Language**
```text
use Youble\YouTubeTransApi\Formatter\SrtFormatter;

$list       = $yt->list('dQw4w9WgXcQ');
$enTrack    = $list->findTranscript(['en']);
$germanCopy = $enTrack->translate('de'); // Auto-translated
$srt        = (new SrtFormatter())->format($germanCopy->fetch());

file_put_contents('captions_de.srt', $srt);
```
 
## 🛠️ Development Setup
Clone the repo

```bash

git clone https://github.com/pulak-ranjan/youble-youtube-trans-api.git
cd youble-youtube-trans-api
```
Install dependencies
```bash

composer install

```
Run tests


```bash
composer test
```
Static analysis

```bash

composer analyse
```
Build examples

```bash

php examples/basic-usage.php

```
> Made with ❤️ by [pulak-ranjan](https://github.com/pulak-ranjan)


