<?php

declare(strict_types=1);

namespace Twirp;

use Psr\Http\Message\RequestInterface;

/**
 * Default noop implementation for ClientHooks.
 * Can be used as a base class to implement only a subset of hooks.
 */
class BaseClientHooks implements ClientHooks
{
    /**
     * {@inheritdoc}
     */
    public function requestPrepared(array $ctx, RequestInterface $request): array
    {
        return $ctx;
    }

    /**
     * {@inheritdoc}
     */
    public function responseReceived(array $ctx): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function error(array $ctx, \Throwable $error): void
    {
    }
}
