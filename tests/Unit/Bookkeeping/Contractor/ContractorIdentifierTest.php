<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Contractor;

use Generator;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use PHPUnit\Framework\TestCase;

final class ContractorIdentifierTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $identifier = new ContractorIdentifier('foo123');
        self::assertEquals('foo123', $identifier->toString());
        self::assertEquals('foo123', $identifier->__toString());
    }

    /**
     * @dataProvider values
     */
    public function testCreatingObjectWithEmptyData(string $value): void
    {
        $this->expectException(ContractorException::class);
        new ContractorIdentifier($value);
    }

    public function values(): Generator
    {
        yield [''];
        yield [' '];
    }
}
