<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Invoice;

use Generator;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceException;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceNetPlnValue;
use PHPUnit\Framework\TestCase;

final class InvoiceNettoPlnValueTest extends TestCase
{
    /**
     * @dataProvider validValues
     */
    public function testItConvertsToString(int $value): void
    {
        $totalValue = new InvoiceNetPlnValue($value);
        self::assertEquals((string) ($value / 100), $totalValue->toString());
        self::assertEquals((string) ($value / 100), (string) $totalValue);
        self::assertEquals($value / 100, $totalValue->toFloat());
    }

    /**
     * @dataProvider invalidValues
     */
    public function testItIsNotEmptyString(int $value): void
    {
        $this->expectException(InvoiceException::class);
        new InvoiceNetPlnValue($value);
    }

    public function validValues(): Generator
    {
        yield [1];
        yield [1000];
        yield [-1000];
        yield [-1];
    }

    public function invalidValues(): Generator
    {
        yield [0];
    }
}
