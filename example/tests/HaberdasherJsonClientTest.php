<?php

declare(strict_types=1);

namespace Tests\Twitch\Twirp\Example;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Prophecy\Argument;
use Psr\Http\Client\ClientInterface;
use Twitch\Twirp\Example\Size;
use Twitch\Twirp\Example\HaberdasherJsonClient;

/**
 * @group example
 */
final class HaberdasherJsonClientTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_makes_request_with_json_serialization(): void
    {
        // Constructs a json client with mocked http.
        $http = $this->prophesize(ClientInterface::class);
        $client = new HaberdasherJsonClient('www.example.com', $http->reveal());

        // Sets http req expectations asserting that correct request body and header is present.
        $isExpectedReq = function (Request $req): bool {
            return 'application/json' === $req->getHeaderLine('content-type') &&
                '{"inches":123}' === (string) $req->getBody();
        };
        $res = new Response(200, [], '{"size":123,"color":"golden","name":"crown"}');
        $http->sendRequest(Argument::that($isExpectedReq))->willReturn($res);

        // Makes a request and asserts response.
        $hat = $client->MakeHat([], (new Size)->setInches(123));
        $this->assertSame(123, $hat->getSize());
        $this->assertSame('golden', $hat->getColor());
        $this->assertSame('crown', $hat->getName());
    }
}
