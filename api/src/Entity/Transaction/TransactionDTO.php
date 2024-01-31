<?php

declare(strict_types=1);

namespace App\Entity\Transaction;

use App\Entity\Currency\Currency;
use App\Validator\ValidationException;
use Doctrine\Common\Collections\ReadableCollection;
use Webmozart\Assert\Assert;

final class TransactionDTO
{
    private int $walletId;
    private int $amount;
    private Currency $currency;
    private Type $type;
    private Reason $reason;

    /**
     * @throws \Exception
     */
    public function __construct(
        int $walletId,
        int $amount,
        string $currency,
        string $type,
        string $reason
    )
    {
        Assert::greaterThan($walletId, 0);

        if (!Currency::tryFrom($currency)) {
            throw new \Exception('Invalid currency');
        }
        if (!Type::tryFrom($type)) {
            throw new \Exception('Invalid currency');
        }
        if (!Reason::tryFrom($reason)) {
            throw new \Exception('Invalid currency');
        }

        $this->walletId = $walletId;
        $this->currency = Currency::from($currency);
        $this->reason = Reason::from($reason);
        $this->type = Type::from($type);
        $this->amount = $amount;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['walletId'],
            $array['amount'],
            $array['currency'],
            $array['type'],
            $array['reason']
        );
    }

    public function getWalletId(): int
    {
        return $this->walletId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getReason(): Reason
    {
        return $this->reason;
    }
}