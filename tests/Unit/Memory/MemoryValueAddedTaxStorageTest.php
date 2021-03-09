<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Memory;

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
}
