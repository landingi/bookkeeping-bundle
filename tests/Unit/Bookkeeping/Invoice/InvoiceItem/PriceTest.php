<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Invoice\InvoiceItem;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Exception\InvoiceItemException;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Price;
use PHPUnit\Framework\TestCase;

final class PriceTest extends TestCase
{
    public function testItConvertsToString(): void
    {
        $price = new Price(1);
        self::assertEquals('0.01', $price->toString());
        self::assertEquals('0.01', (string) $price);
        self::assertEquals(0.01, $price->toFloat());

        $price = new Price(1000);
        self::assertEquals('10', $price->toString());
        self::assertEquals('10', (string) $price);
        self::assertEquals(10, $price->toFloat());
    }

    public function testItIsNotEmptyString(): void
    {
        $this->expectException(InvoiceItemException::class);
        new Price(0);

        $this->expectException(InvoiceItemException::class);
        new Price(-1);

        $this->expectException(InvoiceItemException::class);
        new Price(-1000);
    }
}
