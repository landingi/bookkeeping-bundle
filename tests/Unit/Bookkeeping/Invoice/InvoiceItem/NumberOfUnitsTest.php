<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Invoice\InvoiceItem;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Exception\InvoiceItemException;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\NumberOfUnits;
use PHPUnit\Framework\TestCase;

final class NumberOfUnitsTest extends TestCase
{
    public function testItConvertsToString(): void
    {
        $units = new NumberOfUnits(1);
        self::assertEquals('1', $units->toString());
        self::assertEquals('1', (string) $units);
        self::assertEquals(1, $units->toInteger());

        $units = new NumberOfUnits(0);
        self::assertEquals('0', $units->toString());
        self::assertEquals('0', (string) $units);
        self::assertEquals(0, $units->toInteger());
    }

    public function testItIsNotNegative(): void
    {
        $this->expectException(InvoiceItemException::class);
        new NumberOfUnits(-1);
    }
}
