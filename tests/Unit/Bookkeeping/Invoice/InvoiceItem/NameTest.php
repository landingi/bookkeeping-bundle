<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Invoice\InvoiceItem;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Exception\InvoiceItemException;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Name;
use PHPUnit\Framework\TestCase;

final class NameTest extends TestCase
{
    public function testItConvertsToString(): void
    {
        $name = new Name('name');
        self::assertEquals('name', $name->toString());
        self::assertEquals('name', (string) $name);
    }

    public function testItIsNotEmptyString(): void
    {
        $this->expectException(InvoiceItemException::class);
        new Name('');

        $this->expectException(InvoiceItemException::class);
        new Name(' ');
    }
}
