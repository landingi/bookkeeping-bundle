<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;

interface ValueAddedTaxStorage
{
    public function getByCountry(Country $country): ValueAddedTax;
}
