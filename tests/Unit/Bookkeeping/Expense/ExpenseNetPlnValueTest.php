<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Expense;

use Landingi\BookkeepingBundle\Bookkeeping\Expense\Exception\ExpenseException;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\ExpenseNetPlnValue;
use PHPUnit\Framework\TestCase;

class ExpenseNetPlnValueTest extends TestCase
{
    public function testThrowsOnZeroValue(): void
    {
        $this->expectException(ExpenseException::class);
        (new ExpenseNetPlnValue(0));
    }
}
