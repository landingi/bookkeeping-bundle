<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;
use PHPUnit\Framework\TestCase;

final class StreetTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $street = new Street('foo bar 123');
        self::assertEquals('foo bar 123', $street->toString());
    }

    public function testCreatingObjectWithEmptyData(): void
    {
        self::expectException(AddressException::class);
        new Street('');
    }
}
