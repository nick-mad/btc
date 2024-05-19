<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError' => false,
                'logErrorDetails' => false,
                'logger' => [
                    'name' => 'btc_service',
                    'path' => getenv('docker') ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'mailer' => [
                    'email' => getenv('MAIL_USERNAME') ?: '',
                    'passwd' => getenv('MAIL_PASSWORD') ?: '',
                    'host' => getenv('MAIL_SERVER') ?: '',
                    'port' => getenv('MAIL_PORT') ?: '',
                ],
                'db' => [
                    'driver' => getenv('DB_DRIVER') ?: 'pdo_mysql',
                    'host' => getenv('DB_HOST') ?: 'localhost',
                    'dbname' => getenv('DB_NAME') ?: 'btc',
                    'user' => getenv('DB_USER') ?: 'root',
                    'password' => getenv('DB_PASSWORD') ?: '',
                ],
            ]);
        }
    ]);
};
