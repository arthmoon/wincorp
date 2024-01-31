<?php

declare(strict_types=1);

namespace App\Console;

use App\Entity\Currency\Currency;
use App\Entity\CurrencyExchangeRate\CurrencyExchangeRate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CurrencyExchangeRateCommand extends Command
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->setName('currency:rate:load')
            ->setDescription('Update currency exchange rates');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO: https://github.com/florianv/swap

        while (true) {
            $rate = rand(90, 100) + round(mt_rand() / mt_getrandmax(), 2);

            $this->em->createQueryBuilder()
                ->update(CurrencyExchangeRate::class, 'c')
                ->set('c.rate', $rate)
                ->where('c.firstCurrency = :first and c.secondCurrency = :second')
                ->setParameter(':first', Currency::USD)
                ->setParameter(':second', Currency::RUB)
                ->getQuery()
                ->execute();

            $this->em->createQueryBuilder()
                ->update(CurrencyExchangeRate::class, 'c')
                ->set('c.rate', 1/$rate)
                ->where('c.firstCurrency = :first and c.secondCurrency = :second')
                ->setParameter(':first', Currency::RUB)
                ->setParameter(':second', Currency::USD)
                ->getQuery()
                ->execute();

            $this->em->flush();

            sleep(5);
        }

        return 0;
    }
}