<?php

namespace App\Entity\Transaction;

enum Type: string
{
    case Debit = 'debit';
    case Credit = 'credit';
}