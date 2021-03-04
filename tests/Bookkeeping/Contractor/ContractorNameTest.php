<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\BookkeepingException;
use PHPUnit\Framework\TestCase;

final class ContractorNameTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $identifier = new ContractorName('foo bar');
        self::assertEquals('foo bar', $identifier->toString());
    }

    public function testCreatingObjectWithEmptyData(): void
    {
        self::expectException(BookkeepingException::class);
        new ContractorName('');
    }
}
