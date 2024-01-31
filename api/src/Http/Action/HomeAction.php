<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use stdClass;

final class HomeAction implements RequestHandlerInterface
{
    public function __construct()
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['action' => 'home']);
    }
}
