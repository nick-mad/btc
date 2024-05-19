<?php

require __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use DI\ContainerBuilder;
use App\Application\Settings\SettingsInterface;


$config = new PhpFile(__DIR__ . '/migrations.php');

$containerBuilder = new ContainerBuilder();
$settings = require __DIR__ . '/settings.php';
$settings($containerBuilder);
$container = $containerBuilder->build();
$settings = $container->get(SettingsInterface::class);
$dbSettings = $settings->get('db');

$conn = DriverManager::getConnection($dbSettings);
return DependencyFactory::fromConnection($config, new ExistingConnection($conn));
