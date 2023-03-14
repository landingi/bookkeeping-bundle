<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Functional\Vies;

use DragonBe\Vies\Vies;
use Exception;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\SimpleIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\ValidatedIdentifier;
use Landingi\BookkeepingBundle\Vies\Contractor\Company\ValueAddedTax\ViesIdentifierFactory;
use PHPUnit\Framework\TestCase;

final class ViesValueAddedTaxIdentifierFactoryTest extends TestCase
{
    public function testItIsValidIdentifier(): void
    {
        $factory = new ViesIdentifierFactory(new Vies());
        $identifier = $factory->create('29480969591', 'FR');

        self::assertTrue($identifier instanceof ValidatedIdentifier);
        self::assertEquals('FR29480969591', $identifier->toString());
    }

    public function testItIsValidPolishIdentifier(): void
    {
        $factory = new ViesIdentifierFactory(new Vies());
        $identifier = $factory->create('6762461659', 'PL');

        self::assertTrue($identifier instanceof SimpleIdentifier);
        self::assertEquals('6762461659', $identifier->toString());
    }

    public function testItIsInvalidIdentifier(): void
    {
        $factory = new ViesIdentifierFactory(new Vies());
        $factory->create('333111222', 'DE');

        self::expectException(Exception::class);
    }
}
