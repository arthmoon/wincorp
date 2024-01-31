<?php

declare(strict_types=1);

use App\Entity\Wallet\Wallet;
use App\Entity\Wallet\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
    WalletRepository::class => static function (ContainerInterface $container): WalletRepository {
        $em = $container->get(EntityManagerInterface::class);
        $repo = $em->getRepository(Wallet::class);
        return new WalletRepository($em, $repo);
    },
];
