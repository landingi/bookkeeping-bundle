<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

use Landingi\BookkeepingBundle\Bookkeeping\Expense\ExpenseIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\ExpenseNetPlnValue;

final class ExpenseSummary
{
    private ExpenseIdentifier $identifier;
    private ExpenseNetPlnValue $netPlnValue;

    public function __construct(
        ExpenseIdentifier $identifier,
        ExpenseNetPlnValue $netPlnValue
    ) {
        $this->identifier = $identifier;
        $this->netPlnValue = $netPlnValue;
    }

    public function getIdentifier(): ExpenseIdentifier
    {
        return $this->identifier;
    }

    public function getNetPlnValue(): ExpenseNetPlnValue
    {
        return $this->netPlnValue;
    }
}
