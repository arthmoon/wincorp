<?php

namespace App\Entity\Transaction;

enum Reason: string
{
    case Refund = 'refund';
    case Stock = 'stock';
}