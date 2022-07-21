<?php

declare(strict_types=1);

use App\Http\Action;
use App\Router\StaticRouteGroup as Group;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->get('/', Action\HomeAction::class);

    $app->group('/v1', new Group(static function (RouteCollectorProxy $group): void {
        $group->group('/auth', new Group(static function (RouteCollectorProxy $group): void {
            $group->post('/login', Action\V1\Auth\LoginAction::class);
            $group->get('/logout', Action\V1\Auth\LogoutAction::class);
            $group->post('/reset-password', Action\V1\Auth\ResetPassword\RequestAction::class);
            $group->post('/reset-password/confirm', Action\V1\Auth\ResetPassword\ConfirmAction::class);
            $group->post('/signup', Action\V1\Auth\Signup\RequestAction::class);
            $group->post('/signup/confirm', Action\V1\Auth\Signup\ConfirmAction::class);
            $group->patch('/user/name', Action\V1\Auth\ChangeNameAction::class);
            $group->patch('/user/password', Action\V1\Auth\ChangePasswordAction::class);
            $group->get('/user', Action\V1\Auth\UserAction::class);
            $group->get('/ws-token', Action\V1\Auth\WsTokenAction::class);
        }));

        $group->group('/bank', new Group(static function (RouteCollectorProxy $group): void {
            $group->post('/samples', Action\V1\Biobank\Sample\CreateSampleAction::class);
            $group->post('/samples/delete', Action\V1\Biobank\Sample\DeleteSamplesAction::class);
            $group->post('/samples/all', Action\V1\Biobank\Sample\DeleteAllAction::class);
            $group->post('/samples/upload', Action\V1\Biobank\Sample\UploadSamplesAction::class);
            $group->get('/samples/statistics', Action\V1\Biobank\Sample\GetStatisticsAction::class);
            $group->put('/samples/{id}', Action\V1\Biobank\Sample\UpdateSampleAction::class);
            $group->delete('/samples/{id}', Action\V1\Biobank\Sample\DeleteSampleAction::class);
            $group->get('/samples', Action\V1\Biobank\Sample\GetSamplesAction::class);

            $group->get('/species', Action\V1\Biobank\Specie\GetSpecieAction::class);
            $group->post('/species', Action\V1\Biobank\Specie\CreateSpecieAction::class);
            $group->delete('/species/{id}', Action\V1\Biobank\Specie\DeleteSpecieAction::class);
            $group->put('/species/{id}', Action\V1\Biobank\Specie\UpdateSpecieAction::class);
        }));
    }));
};
