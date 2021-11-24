<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Contractor;

use Generator;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use PHPUnit\Framework\TestCase;

final class ContractorNameTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $name = new ContractorName('foo bar');
        self::assertEquals('foo bar', $name->toString());
        self::assertEquals('foo bar', $name->__toString());
    }

    /**
     * @dataProvider values
     */
    public function testCreatingObjectWithEmptyData(string $value): void
    {
        $this->expectException(ContractorException::class);
        new ContractorName($value);
    }

    public function values(): Generator
    {
        yield [''];
        yield [' '];
    }
}
