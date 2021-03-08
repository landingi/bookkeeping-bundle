<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\BookkeepingException;
use PHPUnit\Framework\TestCase;

final class ContractorIdentifierTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $identifier = new ContractorIdentifier('foo123');
        self::assertEquals('foo123', $identifier->toString());
    }

    public function testCreatingObjectWithEmptyData(): void
    {
        $this->expectException(BookkeepingException::class);
        new ContractorIdentifier('');

        $this->expectException(BookkeepingException::class);
        new ContractorIdentifier(' ');
    }
}
