<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;
use PHPUnit\Framework\TestCase;

final class CountryTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $country = new Country('foo', 'XY');
        self::assertEquals('foo', $country->toString());
    }

    public function testCreatingObjectWithEmptyCountryName(): void
    {
        self::expectException(AddressException::class);
        new Country('', 'XY');
    }

    public function testCreatingObjectWithEmptyCountryIso2Code(): void
    {
        self::expectException(AddressException::class);
        new Country('XYZ', '');
    }

    public function testCreatingObjectWithTooLongCountryIso2Code(): void
    {
        self::expectException(AddressException::class);
        new Country('XYZ', 'XYZ');
    }

    public function testCreatingObjectWithTooShortCountryIso2Code(): void
    {
        self::expectException(AddressException::class);
        new Country('XYZ', 'X');
    }
}
