<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\Condition;

use DateTimeImmutable;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\ExpenseCondition;

final class ExactExpenseDate implements ExpenseCondition
{
    private DateTimeImmutable $date;

    public function __construct(DateTimeImmutable $date)
    {
        $this->date = $date;
    }

    public function __toString(): string
    {
        return $this->date->format('Y-m-d');
    }
}
