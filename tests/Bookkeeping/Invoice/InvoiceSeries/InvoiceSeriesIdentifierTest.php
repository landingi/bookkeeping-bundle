<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;

use PHPUnit\Framework\TestCase;

final class InvoiceSeriesIdentifierTest extends TestCase
{
    public function testItConvertsToString(): void
    {
        $identifier = new InvoiceSeriesIdentifier(100);
        self::assertEquals('100', $identifier->toString());
        self::assertEquals('100', (string) $identifier);
    }

    public function testItConvertsToInteger(): void
    {
        $identifier = new InvoiceSeriesIdentifier(100);
        self::assertEquals(100, $identifier->toInteger());
    }
}
