<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping;

use Landingi\BookkeepingBundle\Bookkeeping\Currency;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\ExpenseIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Expense\ExpenseNetPlnValue;
use Landingi\BookkeepingBundle\Bookkeeping\ExpenseSummary;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceFullNumber;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceNetPlnValue;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoicePaymentMethod;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceTotalValue;
use Landingi\BookkeepingBundle\Bookkeeping\InvoiceSummary;
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
    }
}
