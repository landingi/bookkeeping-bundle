<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

use PHPUnit\Framework\TestCase;

final class InvoiceIdentifierTest extends TestCase
{
    public function testItConvertsToString(): void
    {
        $identifier = new InvoiceIdentifier('identifier');
        self::assertEquals('identifier', $identifier->toString());
        self::assertEquals('identifier', (string) $identifier);
    }
}
