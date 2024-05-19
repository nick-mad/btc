<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use App\Domain\HttpClient\ClientInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\SendmailTransport;
use Symfony\Component\Mailer\MailerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        ClientInterface::class => \DI\autowire(\App\Domain\HttpClient\GuzzleHttpClient::class),
        MailerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $ms = $settings->get('mailer');
            $dsn = 'smtp://' . $ms['email'] . ':' . $ms['passwd'] . '@' . $ms['host'] . ':' . $ms['port'];
            try {
                $transport = Transport::fromDsn($dsn);
            } catch (Exception $e) {
                $transport = new SendmailTransport();
            }
            return new Mailer($transport);
        },
        PDO::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $db = $settings->get('db');

            $dsn = 'mysql:host=' . $db['host'] . ';port=3306;dbname=' . $db['dbname'];
            $connection = new PDO($dsn, $db['user'], $db['password']);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $connection;
        },
    ]);
};
