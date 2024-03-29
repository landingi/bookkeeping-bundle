<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Expense;

final class ExpenseIdentifier
{
    private string $identifier;

    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public function toString(): string
    {
        return $this->identifier;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
