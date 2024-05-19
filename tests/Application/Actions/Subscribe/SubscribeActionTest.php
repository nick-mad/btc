<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Subscribe;

use App\Application\Actions\ActionPayload;
use App\Domain\Email\Email;
use App\Domain\Email\EmailRepository;
use DI\Container;
use Tests\TestCase;

class SubscribeActionTest extends TestCase
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
