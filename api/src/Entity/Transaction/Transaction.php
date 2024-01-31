<?php

declare(strict_types=1);

namespace App\Entity\Transaction;

use App\Entity\Currency\Currency;
use App\Entity\Wallet\Wallet;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

#[ORM\Entity]
#[ORM\Table(name: 'transactions')]
#[ORM\Index(name: 'transactions_search_index', columns: ['created_at', 'reason'])]
class Transaction
{
    #[ORM\Column(type: 'bigint')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'transaction_id', initialValue: 0)]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $currency;

    #[ORM\Column(type: 'string')]
    private string $type;

    #[ORM\Column(type: 'string')]
    private string $reason;

    #[ORM\Column(type: 'integer')]
    private int $amount = 0;

    #[ORM\Column(type: 'datetime_immutable', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: Wallet::class)]
    #[ORM\JoinColumn(name: 'wallet_id', referencedColumnName: 'id')]
    private Wallet|null $wallet;

    private Money $money;

    /**
     * @param Currency $currency
     * @param Type $type
     * @param Reason $reason
     * @param int|string $amount
     *
     * @psalm-param int|numeric-string $amount
     */
    public function __construct(
        Currency $currency,
        Type $type,
        Reason $reason,
        int|string $amount
    )
    {
        $this->money = new Money($amount, new \Money\Currency($currency->value));
        $this->currency = $this->money->getCurrency()->getCode();
        $this->amount = (int)$this->money->getAmount();

        $this->type = $type->value;
        $this->reason = $reason->value;

        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function setWallet(?Wallet $wallet): void
    {
        $this->wallet = $wallet;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

}