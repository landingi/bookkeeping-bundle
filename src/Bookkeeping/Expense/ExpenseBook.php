<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Expense;

use Landingi\BookkeepingBundle\Bookkeeping\Collection;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\Collection\ExpenseCondition;

interface ExpenseBook
{
    public function listSummaries(int $page, ExpenseCondition ...$conditions): Collection;
}
