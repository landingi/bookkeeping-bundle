<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceException;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceTotalValue;
use PHPUnit\Framework\TestCase;

final class InvoiceTotalValueTest extends TestCase
{
    public function testItConvertsToString(): void
    {
        $totalValue = new InvoiceTotalValue(1);
        self::assertEquals('0.01', $totalValue->toString());
        self::assertEquals('0.01', (string) $totalValue);
        self::assertEquals(0.01, $totalValue->toFloat());

        $totalValue = new InvoiceTotalValue(1000);
        self::assertEquals('10', $totalValue->toString());
        self::assertEquals('10', (string) $totalValue);
        self::assertEquals(10, $totalValue->toFloat());
    }

    public function testItIsNotEmptyString(): void
    {
        $this->expectException(InvoiceException::class);
        new InvoiceTotalValue(0);

        $this->expectException(InvoiceException::class);
        new InvoiceTotalValue(-1);

        $this->expectException(InvoiceException::class);
        new InvoiceTotalValue(-1000);
    }
}
