<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\Currency\Currency;
use App\Entity\CurrencyExchangeRate\CurrencyExchangeRate;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class CurrencyExchangeRateFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $rate = rand(90, 100) + round(mt_rand() / mt_getrandmax(), 2);

        $manager->persist(new CurrencyExchangeRate(
            Currency::USD,
            Currency::RUB,
            $rate
        ));

        $manager->persist(new CurrencyExchangeRate(
            Currency::RUB,
            Currency::USD,
            1/$rate
        ));

        $manager->flush();
    }
}