<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTaxIdentifier;

interface IdentifierFactory
{
    public function create(string $identifier, string $country): ValueAddedTaxIdentifier;
}
