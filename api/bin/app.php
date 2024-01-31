#!/usr/bin/env php
<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

use function App\env;

require __DIR__ . '/../vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$cli = new Application('Console');

try {
    /**
     * @var string[] $commands
     * @psalm-suppress MixedArrayAccess
     */
    $commands = $container->get('config')['console']['commands'];

    foreach ($commands as $name) {
        /** @var Command $command */
        $command = $container->get($name);
        $cli->add($command);
    }

    $cli->run();
} catch (\Psr\Container\NotFoundExceptionInterface|\Psr\Container\ContainerExceptionInterface|Exception) {
    exit(1);
}
