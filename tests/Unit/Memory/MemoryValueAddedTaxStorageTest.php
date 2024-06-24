<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Memory;

use Landingi\BookkeepingBundle\Bookkeeping\BookkeepingException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Memory\MemoryValueAddedTaxStorage;
use PHPUnit\Framework\TestCase;

final class MemoryValueAddedTaxStorageTest extends TestCase
{
    private MemoryValueAddedTaxStorage $storage;

    protected function setUp(): void
    {
        $this->storage = new MemoryValueAddedTaxStorage();
    }

    public function testItGetsValueAddedTaxByCountry(): void
    {
        self::assertEquals(23, $this->storage->getByCountry(new Country('PL'))->getRate());
    }

    public function testGreatBritainIsOutsideEuropeanUnion(): void
    {
        $this->expectException(BookkeepingException::class);
        $this->expectExceptionMessage('Country GB is outside European Union and is not eligible for VAT');
        $this->storage->getByCountry(new Country('GB'));
    }

    /**
     * @dataProvider getEuropeanUnionCountriesValueAddedTaxRate
     */
    public function testItHasOnlyValueAddedTaxForEuropeanUnionCountries(string $country, int $tax): void
    {
        self::assertEquals($tax, $this->storage->getByCountry(new Country($country))->getRate());
    }

    public function getEuropeanUnionCountriesValueAddedTaxRate(): \Generator
    {
        yield ['AT', 20];
        yield ['BE', 21];
        yield ['BG', 20];
        yield ['CY', 19];
        yield ['CZ', 21];
        yield ['DE', 19];
        yield ['DK', 25];
        yield ['EE', 22];
        yield ['ES', 21];
        yield ['FI', 24];
        yield ['FR', 20];
        yield ['GR', 24];
        yield ['HR', 25];
        yield ['HU', 27];
        yield ['IE', 23];
        yield ['IT', 22];
        yield ['LT', 21];
        yield ['LV', 21];
        yield ['LU', 17];
        yield ['MT', 18];
        yield ['NL', 21];
        yield ['PL', 23];
        yield ['PT', 23];
        yield ['RO', 19];
        yield ['SK', 20];
        yield ['SI', 22];
        yield ['SE', 25];
    }
}
