<?php

declare(strict_types=1);

namespace App\Entity\CurrencyExchangeRate;

use App\Entity\Currency\Currency;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: 'currency_exchange_rates')]
final class CurrencyExchangeRate
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $firstCurrency;

    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $secondCurrency;

    #[ORM\Column(type: 'float')]
    private float $rate;


    public function __construct(Currency $firstCurrency, Currency $secondCurrency, float $rate)
    {
        Assert::notEq($firstCurrency->value, $secondCurrency->value);
        Assert::greaterThan($rate, 0);

        $this->firstCurrency = $firstCurrency->value;
        $this->secondCurrency = $secondCurrency->value;
        $this->rate = $rate;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFirstCurrency(): string
    {
        return $this->firstCurrency;
    }

    public function getSecondCurrency(): string
    {
        return $this->secondCurrency;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}