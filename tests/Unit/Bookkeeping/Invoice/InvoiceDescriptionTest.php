<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceDescription;
use PHPUnit\Framework\TestCase;

final class InvoiceDescriptionTest extends TestCase
{
    public function testItConvertsToString(): void
    {
        $description = new InvoiceDescription('desc');
        self::assertEquals('desc', $description->toString());
        self::assertEquals('desc', (string) $description);
    }

    public function testItConvertsMultipleToString(): void
    {
        $description = new InvoiceDescription('desc', 'desc', 'desc');
        self::assertEquals('desc desc desc', $description->toString());
        self::assertEquals('desc desc desc', (string) $description);
    }
}
