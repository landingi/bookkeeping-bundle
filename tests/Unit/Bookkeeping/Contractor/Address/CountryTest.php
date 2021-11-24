<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Contractor\Address;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;
use PHPUnit\Framework\TestCase;

final class CountryTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $country = new Country('XY');
        self::assertEquals('XY', $country->toString());
        self::assertEquals('XY', $country->__toString());
    }

    public function testCreatingObjectWithEmptyCountryIso2Code(): void
    {
        $this->expectException(AddressException::class);
        new Country('');
    }

    public function testCreatingObjectWithTooLongCountryIso2Code(): void
    {
        $this->expectException(AddressException::class);
        new Country('XYZ');
    }

    public function testCreatingObjectWithTooShortCountryIso2Code(): void
    {
        $this->expectException(AddressException::class);
        new Country('X');
    }
}
