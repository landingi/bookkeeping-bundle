<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Unit\Bookkeeping\Contractor\Company\ValueAddedTax;

use Landingi\BookkeepingBundle\Bookkeeping\BookkeepingException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\SimpleIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\ValidatedIdentifier;
use PHPUnit\Framework\TestCase;

final class ValidatedIdentifierTest extends TestCase
{
    public function testCreatingObjectWithValidData(): void
    {
        $identifier = new ValidatedIdentifier(new SimpleIdentifier('foo123'), new Country('PL'));
        self::assertEquals('PLfoo123', $identifier->toString());
        self::assertEquals('PLfoo123', $identifier->__toString());
    }
}
