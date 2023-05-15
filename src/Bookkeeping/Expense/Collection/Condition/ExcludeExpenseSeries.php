<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\Condition;

use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\ExpenseCondition;

final class ExcludeExpenseSeries implements ExpenseCondition
{
    private string $expenseSeries;

    public function __construct(string $expenseSeries)
    {
        $this->expenseSeries = $expenseSeries;
    }

    public function __toString(): string
    {
        return $this->expenseSeries;
    }
}
