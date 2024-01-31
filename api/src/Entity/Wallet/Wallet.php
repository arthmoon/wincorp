<?php

declare(strict_types=1);

namespace App\Entity\Wallet;

use App\Entity\Currency\Currency;
use Doctrine\ORM\Mapping as ORM;
use Money\Currency as MoneyCurrency;
use Money\Money;

#[ORM\Entity]
#[ORM\Table(name: 'wallets')]
final class Wallet
{
    #[ORM\Column(type: 'bigint')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'wallet_id', initialValue: 0)]
    private string $id;

    #[ORM\Column(type: 'integer')]
    private int $amount = 0;

    #[ORM\Column(type: 'string')]
    private string $currency;

    private Money $money;

    /**
     * @param int|string $amount
     * @param Currency $currency
     *
     * @psalm-param int|numeric-string $amount
     */
    public function __construct(int|string $amount, Currency $currency)
    {
        $this->money = new Money($amount, new MoneyCurrency($currency->value));

        $this->currency = $this->money->getCurrency()->getCode();
        $this->amount = (int)$this->money->getAmount();
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function setMoney(Money $money): void
    {
        $this->money = $money;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }
}