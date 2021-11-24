<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Functional\Vies;

use DragonBe\Vies\Vies;
use Landingi\BookkeepingBundle\Vies\Contractor\Company\ValueAddedTax\ViesIdentifierFactory;
use PHPUnit\Framework\TestCase;

final class ViesValueAddedTaxIdentifierFactoryTest extends TestCase
{
    public function testItIsValidIdentifier(): void
    {
        $factory = new ViesIdentifierFactory(new Vies());
        $identifier = $factory->create('6762461659', 'PL');

        self::assertEquals('PL6762461659', $identifier->toString());
    }

    public function testItIsInvalidIdentifier(): void
    {
        $factory = new ViesIdentifierFactory(new Vies());
        $identifier = $factory->create('333111222', 'PL');

        self::assertEquals('333111222', $identifier->toString());
    }
}
