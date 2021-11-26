<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Contractor\Company\ValueAddedTax;

use Landingi\BookkeepingBundle\Bookkeeping\BookkeepingException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\SimpleIdentifier;
use PHPUnit\Framework\TestCase;

final class SimpleIdentifierTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $identifier = new SimpleIdentifier('foo123');
        self::assertEquals('foo123', $identifier->toString());
        self::assertEquals('foo123', $identifier->__toString());
    }

    public function testCreatingObjectWithEmptyData(): void
    {
        $this->expectException(BookkeepingException::class);
        new SimpleIdentifier('');
    }
}
