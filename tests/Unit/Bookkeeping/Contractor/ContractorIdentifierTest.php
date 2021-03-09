<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\BookkeepingException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
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
