<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Contractor\Address;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;
use PHPUnit\Framework\TestCase;

final class CityTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $city = new City('foo');
        self::assertEquals('foo', $city->toString());
        self::assertEquals('foo', $city->__toString());
    }

    public function testCreatingObjectWithEmptyData(): void
    {
        $this->expectException(AddressException::class);
        new City('');
    }
}
