<?php

declare(strict_types=1);

namespace Tests\Twirp;

use Exception;
use GuzzleHttp\Psr7\Request;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Twirp\ChainClientHooks;
use Twirp\ClientHooks;

final class ChainClientHooksTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ChainClientHooks
     */
    private $chained;

    /**
     * @var ObjectProphecy
     */
    private $hook1;

    /**
     * @var ObjectProphecy
     */
    private $hook2;

    public function setUp(): void
    {
        $this->hook1 = $this->prophesize(ClientHooks::class);
        $this->hook2 = $this->prophesize(ClientHooks::class);

        $this->chained = new ChainClientHooks($this->hook1->reveal(), $this->hook2->reveal());
    }

    public function testRequestPrepared(): void
    {
        $this->hook1->requestPrepared(Argument::cetera())->will(function ($args) {
            return array_merge($args[0], ['hook1' => true]);
        });
        $this->hook2->requestPrepared(Argument::cetera())->will(function ($args) {
            return array_merge($args[0], ['hook2' => true]);
        });

        $actual = $this->chained->requestPrepared([], new Request('POST', '/'));

        $this->assertEquals([
            'hook1' => true,
            'hook2' => true,
        ], $actual);
    }

    public function testResponseReceived(): void
    {
        $ctx = ['key' => 'value'];

        $this->hook1->responseReceived($ctx)->shouldBeCalled();
        $this->hook2->responseReceived($ctx)->shouldBeCalled();

        $this->chained->responseReceived($ctx);
    }

    public function testError(): void
    {
        $ctx = ['key' => 'value'];
        $error = new Exception;

        $this->hook1->error($ctx, $error)->shouldBeCalled();
        $this->hook2->error($ctx, $error)->shouldBeCalled();

        $this->chained->error($ctx, $error);
    }
}
