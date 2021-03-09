<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Contractor\Address;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;
use PHPUnit\Framework\TestCase;

final class PostalCodeTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $postalCode = new PostalCode('111-111');
        self::assertEquals('111-111', $postalCode->toString());
    }

    public function testCreatingObjectWithEmptyData(): void
    {
        $this->expectException(AddressException::class);
        new PostalCode('');
    }
}
