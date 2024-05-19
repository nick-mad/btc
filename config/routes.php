<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\Rate\ViewRateAction;
use App\Application\Actions\Subscribe\SubscribeAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->group('/api', function (Group $group) {
        $group->get('/rate', ViewRateAction::class);
        $group->post('/subscribe', SubscribeAction::class);
    });
};
