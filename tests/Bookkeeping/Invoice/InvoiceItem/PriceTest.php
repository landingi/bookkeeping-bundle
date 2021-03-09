<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Exception\InvoiceItemException;
use PHPUnit\Framework\TestCase;

final class PriceTest extends TestCase
{
    public function testItConvertsToString(): void
    {
        $price = new Price(0);
        self::assertEquals('0', $price->toString());
        self::assertEquals('0', (string) $price);
        self::assertEquals(0, $price->toFloat());
    }

    public function testItIsNotEmptyString(): void
    {
        $this->expectException(InvoiceItemException::class);
        new Price(-1);

        $this->expectException(InvoiceItemException::class);
        new Price(-1000);
    }
}
