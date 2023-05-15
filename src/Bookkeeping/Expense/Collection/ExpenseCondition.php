<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection;

use Landingi\BookkeepingBundle\Bookkeeping\Collection\CollectionCondition;

interface ExpenseCondition extends CollectionCondition
{
    public function __toString(): string;
}
