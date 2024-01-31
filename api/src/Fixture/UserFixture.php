<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\Currency\Currency;
use App\Entity\Transaction\Reason;
use App\Entity\Transaction\Transaction;
use App\Entity\Transaction\Type;
use App\Entity\User\Id;
use App\Entity\User\Token;
use App\Entity\User\User;
use App\Entity\Wallet\Wallet;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

final class UserFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $wallet = new Wallet(rand(0, 1000000), rand(0,1) ? Currency::RUB : Currency::USD);
            $manager->persist($wallet);

            for ($j = 0; $j < 10; $j++) {
                $amount = rand(-10000, 10000);
                $transaction = new Transaction(
                    rand(0,1) ? Currency::RUB : Currency::USD,
                    $amount < 0 ? Type::Credit : Type::Debit,
                    rand(0,1) ? Reason::Stock : Reason::Refund,
                    $amount
                );

                $transaction->setWallet($wallet);
                $manager->persist($transaction);
            }

            $user = new User(
                Id::generate(),
                new Token(
                    Uuid::uuid4()->toString(),
                    new DateTimeImmutable('+30 days')
                )
            );

            $user->setWallet($wallet);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
