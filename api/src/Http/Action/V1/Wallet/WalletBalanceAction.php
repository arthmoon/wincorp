<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Wallet;

use App\Entity\Currency\Currency;
use App\Entity\CurrencyExchangeRate\CurrencyExchangeRate;
use App\Entity\Wallet\Wallet;
use App\Http\Response\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class WalletBalanceAction implements RequestHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     * @throws NonUniqueResultException
     * @throws \JsonException
     * @throws NoResultException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $wallet_id = $request->getQueryParams()['id'] ?? 0;
        if (!$wallet_id) {
            return new JsonResponse([
                'error' => true,
                'msg' => 'Invalid wallet id'
            ]);
        }

        $wallet = $this->em->createQueryBuilder()
            ->select('w.id', 'w.amount', 'w.currency')
            ->from(Wallet::class, 'w')
            ->where('w.id = :id')
            ->setParameter(':id', $wallet_id)
            ->getQuery()
            ->getSingleResult();

        return new JsonResponse([
            'wallet' => $wallet
        ]);
    }
}