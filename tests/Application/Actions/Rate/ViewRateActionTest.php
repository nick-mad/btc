<?php

declare(strict_types=1);

namespace Application\Actions\Rate;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use DI\Container;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

class ViewRateActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $expectedPayload = '';
        $payload = '';
        $this->assertEquals($expectedPayload, $payload);
    }
}
