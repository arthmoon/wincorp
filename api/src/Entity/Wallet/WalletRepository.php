<?php

declare(strict_types=1);

namespace App\Entity\Wallet;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;
final class WalletRepository
{
    /**
     * @var EntityRepository<Wallet>
     */
    private EntityRepository $repository;
    private EntityManagerInterface $em;

    /**
     * @param EntityRepository<Wallet> $repository
     */
    public function __construct(EntityManagerInterface $em, EntityRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    public function get(string $id): Wallet
    {
        $wallet = $this->repository->findOneBy(['id' => $id]);
        if ($wallet === null) {
            throw new DomainException('Wallet is not found.');
        }
        return $wallet;
    }

    public function add(Wallet $wallet): void
    {
        $this->em->persist($wallet);
    }
}