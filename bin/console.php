<?php

use App\Application\Console\SendEmailsCommand;
use DI\ContainerBuilder;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

require_once __DIR__ . '/../vendor/autoload.php';

$env = (new ArgvInput())->getParameterOption(['--env', '-e'], 'dev');

if ($env) {
    $_ENV['APP_ENV'] = $env;
}

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Set up settings
$settings = require __DIR__ . '/../config/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/../config/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
$repositories = require __DIR__ . '/../config/repositories.php';
$repositories($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

try {
    /** @var Application $application */
    $application = $container->get(Application::class);

    $application->add($container->get(SendEmailsCommand::class));

    exit($application->run());
} catch (Throwable $exception) {
    echo $exception->getMessage();
    exit(1);
}
