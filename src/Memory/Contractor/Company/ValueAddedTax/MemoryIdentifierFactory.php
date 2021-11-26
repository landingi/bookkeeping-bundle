<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Memory\Contractor\Company\ValueAddedTax;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\IdentifierFactory;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\SimpleIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\ValidatedIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTaxIdentifier;

final class MemoryIdentifierFactory implements IdentifierFactory
{
    /**
     * @throws \Landingi\BookkeepingBundle\Vies\ViesException
     */
    public function create(string $identifier, string $country): ValueAddedTaxIdentifier
    {
        if (true === empty($country)) {
            return new SimpleIdentifier($identifier);
        }

        return new ValidatedIdentifier(new SimpleIdentifier($identifier), new Country($country));
    }
}
