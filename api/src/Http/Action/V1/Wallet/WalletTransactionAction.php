<?php

namespace App\Http\Action\V1\Wallet;

use App\Entity\Currency\Currency;
use App\Entity\CurrencyExchangeRate\CurrencyExchangeRate;
use App\Entity\Transaction\Reason;
use App\Entity\Transaction\Transaction;
use App\Entity\Transaction\TransactionDTO;
use App\Entity\Transaction\Type;
use App\Entity\Wallet\Wallet;
use App\Entity\Wallet\WalletRepository;
use App\Http\Response\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Money\Currencies\ISOCurrencies;
use Money\Currency as MoneyCurrency;
use Money\Exchange\FixedExchange;
use Money\Money;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Money\Converter;

final class WalletTransactionAction implements RequestHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private WalletRepository $wallets
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $dto = TransactionDTO::fromArray($request->getParsedBody());

        $transactionMoney = new Money(
            $dto->getAmount(),
            new MoneyCurrency($dto->getCurrency()->value)
        );

        $wallet = $this->wallets->get((string) $dto->getWalletId());
        $walletMoney = new Money($wallet->getAmount(), new MoneyCurrency($wallet->getCurrency()));

        if ($dto->getCurrency()->value !== $wallet->getCurrency()) {
            $rate = $this->em->createQueryBuilder()
                ->select('c.rate')
                ->from(CurrencyExchangeRate::class, 'c')
                ->where('c.firstCurrency = :first and c.secondCurrency = :second')
                ->setParameter(':first', $dto->getCurrency()->value)
                ->setParameter(':second', $wallet->getCurrency())
                ->getQuery()
                ->getSingleScalarResult();

            if (!$rate) {
                return new JsonResponse([
                    'error' => true,
                    'msg' => 'Exchange rate not found'
                ]);
            }

            $converter = new Converter(new ISOCurrencies(), new FixedExchange([
                $dto->getCurrency()->value => [
                    $wallet->getCurrency() => $rate
                ]
            ]));

            $convertedMoney = $converter->convert($transactionMoney, new MoneyCurrency($wallet->getCurrency()));
            $resultMoney = $walletMoney->add($convertedMoney);
        } else {
            $resultMoney = $walletMoney->add($transactionMoney);
        }

        $this->em->getConnection()->beginTransaction();
        try {
            $wallet->setAmount($resultMoney->getAmount());
            $this->em->persist($wallet);

            $transaction = new Transaction(
                $dto->getCurrency(),
                $dto->getType(),
                $dto->getReason(),
                $transactionMoney->getAmount()
            );

            $transaction->setWallet($wallet);

            $this->em->persist($transaction);
            $this->em->flush();

            $this->em->getConnection()->commit();

        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();

            return new JsonResponse([
                'error' => 'true',
                'msg' => 'Transaction failed'
            ]);
        }

        return new JsonResponse([
            'walletPrevAmount' => $walletMoney->getAmount(),
            'walletCurrency' => $wallet->getCurrency(),
            'transactionAmount' => $transactionMoney->getAmount(),
            'rate' => $rate ?? 1,
            'resultAmount' => $resultMoney->getAmount()
        ]);
    }
}