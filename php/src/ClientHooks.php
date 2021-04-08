<?php

declare(strict_types=1);

namespace Twirp;

use Psr\Http\Message\RequestInterface;

/**
 * ClientHooks is a container for callbacks that can instrument a
 * Twirp-generated client. These callbacks all accept a context and some return
 * a context. They can use this to add to the context, appending values or
 * deadlines to it.
 *
 * The requestPrepared hook is special because it can throw exceptions. If it
 * throws an exception, handling for that request will be stopped at that
 * point. The error hook will then be triggered.
 *
 * The requestPrepared hook will always be called first and will be called for
 * each outgoing request from the Twirp client. The last hook to be called will
 * either be error or responseReceived, so be sure to handle both cases in your
 * hooks.
 */
interface ClientHooks
{
    /**
     * RequestPrepared is called as soon as a request has been created and
     * before it has been sent to the Twirp server.
     *
     * @throws \Throwable
     */
    public function requestPrepared(array $ctx, RequestInterface $request): array;

    /**
     * ResponseReceived is called after a request has finished sending. Since
     * this is terminal, the context is not returned. ResponseReceived will not
     * be called in the case of an error being returned from the request.
     */
    public function responseReceived(array $ctx): void;

    /**
     * Error hook is called whenever an error occurs during the sending of a
     * request. The Error is passed as an argument to the hook.
     */
    public function error(array $ctx, \Throwable $error): void;
}
