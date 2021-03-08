<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\BookkeepingException;
use PHPUnit\Framework\TestCase;

final class ContractorEmailTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $email = new ContractorEmail('foo bar');
        self::assertEquals('foo bar', $email->toString());
    }

    public function testCreatingObjectWithEmptyData(): void
    {
        self::expectException(BookkeepingException::class);
        new ContractorEmail('');
    }
}
