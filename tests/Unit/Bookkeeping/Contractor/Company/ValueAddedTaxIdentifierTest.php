<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Contractor\Company;

use Landingi\BookkeepingBundle\Bookkeeping\BookkeepingException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTaxIdentifier;
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
        $this->expectException(BookkeepingException::class);
        new ValueAddedTaxIdentifier('');
    }
}
