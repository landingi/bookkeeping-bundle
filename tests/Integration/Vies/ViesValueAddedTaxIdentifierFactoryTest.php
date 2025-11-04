<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Integration\Vies;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\SimpleIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\ValidatedIdentifier;
use Landingi\BookkeepingBundle\Integration\IntegrationTestCase;
use Landingi\BookkeepingBundle\Vies\Client\ViesClient;
use Landingi\BookkeepingBundle\Vies\Contractor\Company\ValueAddedTax\ViesIdentifierFactory;
use Landingi\BookkeepingBundle\Vies\Contractor\InvalidViesIdentifierException;

final class ViesValueAddedTaxIdentifierFactoryTest extends IntegrationTestCase
{
    public function testItIsValidIdentifier(): void
    {
        $factory = new ViesIdentifierFactory(new ViesClient());
        $identifier = $factory->create('B40582447', 'ES');

        self::assertTrue($identifier instanceof ValidatedIdentifier);
        self::assertEquals('ESB40582447', $identifier->toString());
    }

    public function testItIsValidIdentifierForCountryOutsideEu(): void
    {
        $factory = new ViesIdentifierFactory(new ViesClient());
        $identifier = $factory->create('105454931', 'GB');

        self::assertTrue($identifier instanceof ValidatedIdentifier);
        self::assertEquals('105454931', $identifier->toString());
    }

    public function testItIsValidPolishIdentifier(): void
    {
        $factory = new ViesIdentifierFactory(new ViesClient());
        $identifier = $factory->create('6762461659', 'PL');

        self::assertTrue($identifier instanceof SimpleIdentifier);
        self::assertEquals('6762461659', $identifier->toString());
    }

    public function testItIsInvalidIdentifier(): void
    {
        self::expectException(InvalidViesIdentifierException::class);

        $factory = new ViesIdentifierFactory(new ViesClient());
        $factory->create('333111222', 'DE');
    }

    public function testItThrowsWhenViesThrowsGenericException(): void
    {
        self::expectException(InvalidViesIdentifierException::class);

        $factory = new ViesIdentifierFactory(new ViesClient());
        $factory->create('333111222', 'DE');
    }
}
