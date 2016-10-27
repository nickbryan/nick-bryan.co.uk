<?php

namespace App\Middleware;

use Slim\Handlers\NotFound;
use Slim\Http\Request;
use Slim\Http\Response;

final class HandlePageNotFound
{
    const NOT_FOUND_CODE = 404;

    /**
     * @var NotFound
     */
    private $handler;

    public function __construct(NotFound $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        /** @var Response $response */
        $response = $next($request, $response);

        if ($response->getStatusCode() === self::NOT_FOUND_CODE && $response->getBody()->getSize() === 0) {
            return call_user_func($this->handler, $request, $response);
        }

        return $response;
    }
}
