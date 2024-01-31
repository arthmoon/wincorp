<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Exception\UnauthorizedHttpException;
use App\Http\Response\JsonResponse;
use PHPUnit\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

use function App\env;

final class ApiTokenHandler implements MiddlewareInterface
{
    public function __construct() {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $request->getHeader('Authorization');
        if (count($token) === 0) {
            throw new UnauthorizedHttpException($request, '');
        }

        $token = trim(str_replace('Bearer ', '', $token[0]));

        if ($token !== env('API_TOKEN')) {
            throw new UnauthorizedHttpException($request, '');
        }

        return $handler->handle($request);
    }
}