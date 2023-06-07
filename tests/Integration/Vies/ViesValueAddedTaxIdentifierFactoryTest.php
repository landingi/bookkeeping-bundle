<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Integration\Vies;

use DragonBe\Vies\Vies;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\SimpleIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\ValidatedIdentifier;
use Landingi\BookkeepingBundle\Integration\IntegrationTestCase;
use Landingi\BookkeepingBundle\Vies\Contractor\Company\ValueAddedTax\ViesIdentifierFactory;
use Landingi\BookkeepingBundle\Vies\Contractor\InvalidViesIdentifierException;

final class ViesValueAddedTaxIdentifierFactoryTest extends IntegrationTestCase
{
    public function testItIsValidIdentifier(): void
    {
        $factory = new ViesIdentifierFactory(new Vies());
        $identifier = $factory->create('29480969591', 'FR');

        self::assertTrue($identifier instanceof ValidatedIdentifier);
        self::assertEquals('FR29480969591', $identifier->toString());
        sleep(1);
    }

    public function testItIsValidPolishIdentifier(): void
    {
        $factory = new ViesIdentifierFactory(new Vies());
        $identifier = $factory->create('6762461659', 'PL');

        self::assertTrue($identifier instanceof SimpleIdentifier);
        self::assertEquals('6762461659', $identifier->toString());
        sleep(1);
    }

    public function testItIsInvalidIdentifier(): void
    {
        self::expectException(InvalidViesIdentifierException::class);

        $factory = new ViesIdentifierFactory(new Vies());
        $factory->create('333111222', 'DE');
        sleep(1);
    }
}
