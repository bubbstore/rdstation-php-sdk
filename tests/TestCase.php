<?php

namespace bubbstore\RDStation;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

abstract class TestCase extends FrameworkTestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * Mocks Guzzle HTTP client.
     *
     * @param  string|null $responseBodyFile
     *
     * @return \GuzzleHttp\Client
     */
    protected function mockHttpClient($responseBodyFile = null)
    {
        $mock = new MockHandler;

        if ($responseBodyFile) {
            $mock->append(new Response(200, [], file_get_contents(realpath($responseBodyFile))));
        }

        return new Client([
            'handler' => HandlerStack::create($mock),
        ]);
    }
}