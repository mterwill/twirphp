<?php

declare(strict_types=1);

namespace Tests\Twirp;

use Exception;
use GuzzleHttp\Psr7\Request;
use Twirp\BaseClientHooks;

final class BaseClientHooksTest extends \PHPUnit\Framework\TestCase
{
    public function testRequestPrepared(): void
    {
        $ctx = ['key' => 'value'];
        $this->assertEquals(
            $ctx,
            (new BaseClientHooks)->requestPrepared($ctx, new Request('POST', '/'))
        );
    }

    public function testResponseReceived(): void
    {
        $this->expectNotToPerformAssertions();

        (new BaseClientHooks)->responseReceived([]);
    }

    public function testError(): void
    {
        $this->expectNotToPerformAssertions();

        (new BaseClientHooks)->error([], new Exception);
    }
}
