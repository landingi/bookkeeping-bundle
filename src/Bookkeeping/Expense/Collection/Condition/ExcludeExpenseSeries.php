<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\Condition;

use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\ExpenseCondition;

final class ExcludeExpenseSeries implements ExpenseCondition
{
    private string $expenseNumberFragment;

    /**
     * This accepts a fragment of the expense's full number, to compare against
     */
    public function __construct(string $expenseNumberFragment)
    {
        $this->expenseNumberFragment = $expenseNumberFragment;
    }

    public function __toString(): string
    {
        return $this->expenseNumberFragment;
    }
}
