<?php

declare(strict_types=1);

namespace App\Entity\Currency;

enum Currency: string
{
    case USD = 'USD';
    case RUB = 'RUB';
}