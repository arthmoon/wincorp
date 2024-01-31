<?php

declare(strict_types=1);

use App\Console\CurrencyExchangeRateCommand;
use App\Console\FixturesLoadCommand;
use Doctrine\Migrations;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Command\SchemaTool;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Psr\Container\ContainerInterface;

return [
    FixturesLoadCommand::class => static function (ContainerInterface $container) {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{fixture_paths:string[]} $config
         */
        $config = $container->get('config')['console'];

        return new FixturesLoadCommand(
            $container->get(EntityManagerInterface::class),
            $config['fixture_paths'],
        );
    },

    CurrencyExchangeRateCommand::class => static fn (ContainerInterface $container): CurrencyExchangeRateCommand => new CurrencyExchangeRateCommand($container->get(EntityManagerInterface::class)),

    DropCommand::class => static fn (ContainerInterface $container): DropCommand => new DropCommand($container->get(EntityManagerProvider::class)),

    'config' => [
        'console' => [
            'commands' => [
                CurrencyExchangeRateCommand::class,
                FixturesLoadCommand::class,
                SchemaTool\DropCommand::class,
                Migrations\Tools\Console\Command\DiffCommand::class,
                Migrations\Tools\Console\Command\GenerateCommand::class,
            ],
            'fixture_paths' => [
                __DIR__ . '/../../src/Fixture',
            ],
        ],
    ],
];
