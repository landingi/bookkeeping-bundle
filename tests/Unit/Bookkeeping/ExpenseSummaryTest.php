<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping;

use Landingi\BookkeepingBundle\Bookkeeping\Expense\ExpenseIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\ExpenseNetPlnValue;
use Landingi\BookkeepingBundle\Bookkeeping\ExpenseSummary;
use PHPUnit\Framework\TestCase;

class ExpenseSummaryTest extends TestCase
{
    public function testGettersReturnProperValues(): void
    {
        $summary = new ExpenseSummary(
            new ExpenseIdentifier('123'),
            new ExpenseNetPlnValue(10000),
        );
        $this->assertEquals('123', (string) $summary->getIdentifier());
        $this->assertEquals(100.00, $summary->getNetPlnValue()->toFloat());
        $this->assertEquals('100', (string) $summary->getNetPlnValue());
    }
}
