<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company;

use Landingi\BookkeepingBundle\Bookkeeping\BookkeepingException;
use PHPUnit\Framework\TestCase;

final class ValueAddedTaxIdentifierTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $identifier = new ValueAddedTaxIdentifier('foo123');
        self::assertEquals('foo123', $identifier->toString());
    }

    public function testCreatingObjectWithEmptyData(): void
    {
        self::expectException(BookkeepingException::class);
        new ValueAddedTaxIdentifier('');
    }
}
