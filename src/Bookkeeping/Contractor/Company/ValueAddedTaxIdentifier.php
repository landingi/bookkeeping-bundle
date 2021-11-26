<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company;

interface ValueAddedTaxIdentifier
{
    public function toString(): string;
}
