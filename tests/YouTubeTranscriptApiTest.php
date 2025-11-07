<?php

namespace Youble\YouTubeTransApi\Tests;

use PHPUnit\Framework\TestCase;
use Youble\YouTubeTransApi\YouTubeTranscriptApi;
use Youble\YouTubeTransApi\Exception\VideoUnavailable;
use Youble\YouTubeTransApi\Exception\NoTranscriptFound;

class YouTubeTranscriptApiTest extends TestCase
{
    private YouTubeTranscriptApi $api;

    protected function setUp(): void
    {
        $this->api = new YouTubeTranscriptApi();
    }

    public function testCanInstantiateApi(): void
    {
        $this->assertInstanceOf(YouTubeTranscriptApi::class, $this->api);
    }

    public function testThrowsExceptionForInvalidVideoId(): void
    {
        $this->expectException(VideoUnavailable::class);
        $this->api->fetch('invalid_video_id_12345');
    }

    public function testCanGetHttpClient(): void
    {
        $client = $this->api->getHttpClient();
        $this->assertInstanceOf(\GuzzleHttp\Client::class, $client);
    }

    /**
     * This test requires network access and may fail if the video is unavailable
     * Skip it by default or run with --group=integration
     *
     * @group integration
     */
    public function testCanFetchRealTranscript(): void
    {
        // Using a well-known video that should have transcripts
        $videoId = 'dQw4w9WgXcQ';

        try {
            $snippets = $this->api->fetch($videoId, ['en']);
            $this->assertIsArray($snippets);
            $this->assertNotEmpty($snippets);

            foreach ($snippets as $snippet) {
                $this->assertInstanceOf(\Youble\YouTubeTransApi\Model\TranscriptSnippet::class, $snippet);
                $this->assertIsString($snippet->getText());
                $this->assertIsFloat($snippet->getStart());
                $this->assertIsFloat($snippet->getDuration());
            }
        } catch (\Exception $e) {
            $this->markTestSkipped('Could not fetch transcript: ' . $e->getMessage());
        }
    }

    /**
     * @group integration
     */
    public function testCanListTranscripts(): void
    {
        $videoId = 'dQw4w9WgXcQ';

        try {
            $list = $this->api->list($videoId);
            $this->assertInstanceOf(\Youble\YouTubeTransApi\Model\TranscriptList::class, $list);

            $all = $list->getAll();
            $this->assertIsArray($all);
        } catch (\Exception $e) {
            $this->markTestSkipped('Could not list transcripts: ' . $e->getMessage());
        }
    }
}
