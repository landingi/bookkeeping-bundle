<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTaxIdentifier;

/**
 * The class represents VIES validated tax identifier.
 */
final class ValidatedIdentifier implements ValueAddedTaxIdentifier
{
    private SimpleIdentifier $identifier;
    private Country $country;

    public function __construct(SimpleIdentifier $identifier, Country $country)
    {
        $this->identifier = $identifier;
        $this->country = $country;
    }

    public function toString(): string
    {
        return $this->country->toString() . $this->identifier->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
