<?php

declare(strict_types=1);

use App\Domain\Email\EmailRepository;
use App\Infrastructure\Persistence\Email\InFileEmailRepository;
use App\Infrastructure\Persistence\Email\InDbEmailRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        //Якщо потрібно зберігати у файл
        //EmailRepository::class => \DI\autowire(InFileEmailRepository::class),
        //Якщо потрібно зберігати у бд
        EmailRepository::class => \DI\autowire(InDbEmailRepository::class),
    ]);
};
