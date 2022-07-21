<?php

declare(strict_types=1);

use App\Http\Middleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return static function (App $app): void {
    $app->add(Middleware\Auth\Authenticate::class);
    $app->add(Middleware\InvalidArgumentExceptionHandler::class);
    $app->add(Middleware\DomainExceptionHandler::class);
    $app->add(Middleware\ValidationExceptionHandler::class);
    $app->add(Middleware\ClearEmptyInput::class);
    $app->add(Middleware\AddRouteParams::class);
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->add(Middleware\RateLimiter::class);
    $app->add(ErrorMiddleware::class);
};
