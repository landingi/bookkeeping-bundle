<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Expense\Factory;

use Landingi\BookkeepingBundle\Bookkeeping\Expense\ExpenseIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\ExpenseNetPlnValue;
use Landingi\BookkeepingBundle\Bookkeeping\ExpenseSummary;

final class ExpenseSummaryFactory
{
    public function getSummaryFromApiData(array $data): ExpenseSummary
    {
        return new ExpenseSummary(
            new ExpenseIdentifier((string) $data['id']),
            new ExpenseNetPlnValue((int) ($data['netto'] * 100))
        );
    }
}
