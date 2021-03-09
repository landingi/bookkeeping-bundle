<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\BookkeepingException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use PHPUnit\Framework\TestCase;

final class ContractorNameTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $name = new ContractorName('foo bar');
        self::assertEquals('foo bar', $name->toString());
    }

    public function testCreatingObjectWithEmptyData(): void
    {
        $this->expectException(BookkeepingException::class);
        new ContractorName('');

        $this->expectException(BookkeepingException::class);
        new ContractorName(' ');
    }
}
