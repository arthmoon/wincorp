<?php

declare(strict_types=1);

use App\Http\Action;
use App\Router\StaticRouteGroup as Group;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->get('/', Action\HomeAction::class);

    $app->group('/v1', new Group(static function (RouteCollectorProxy $group): void {
        $group->group('/wallet', new Group(static function (RouteCollectorProxy $group): void {
            $group->get('/balance', Action\V1\Wallet\WalletBalanceAction::class);
            $group->post('/transaction', Action\V1\Wallet\WalletTransactionAction::class);
        }));
    }));
};
