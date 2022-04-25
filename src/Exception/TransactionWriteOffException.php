<?php

declare(strict_types=1);

namespace App\Exception;

use App\Entity\User;
use LogicException;

class TransactionWriteOffException extends LogicException
{
    public function __construct(User $user, int $amount)
    {
        $message = sprintf(
            'Can\'t write-off %d from user "%s" (id: %d, current amount: %d)',
            $amount,
            $user->getName(),
            $user->getId(),
            $user->getAmount()
        );

        parent::__construct($message);
    }
}
