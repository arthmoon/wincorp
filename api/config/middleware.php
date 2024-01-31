<?php

declare(strict_types=1);

use App\Http\Middleware;
use Middlewares\ContentLanguage;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return static function (App $app): void {
    $app->add(Middleware\ApiTokenHandler::class);
    $app->add(Middleware\DenormalizationExceptionHandler::class);
    $app->add(Middleware\ClearEmptyInput::class);
    $app->addBodyParsingMiddleware();
    $app->add(ErrorMiddleware::class);
};
