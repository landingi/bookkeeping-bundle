<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Memory;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\ValueAddedTax;
use Landingi\BookkeepingBundle\Bookkeeping\ValueAddedTaxStorage;

final class MemoryValueAddedTaxStorage implements ValueAddedTaxStorage
{
    private const MEMORY = [
        'AT' =>  20,
        'BE' =>  21,
        'BG' =>  20,
        'CY' =>  19,
        'CZ' =>  21,
        'DE' =>  19,
        'DK' =>  25,
        'EE' =>  20,
        'ES' =>  21,
        'FI' =>  24,
        'FR' =>  20,
        'GB' =>  20,
        'GR' =>  24,
        'HR' =>  25,
        'HU' =>  27,
        'IE' =>  23,
        'IT' =>  22,
        'LT' =>  21,
        'LV' =>  21,
        'LU' =>  17,
        'MT' =>  18,
        'NL' =>  21,
        'PL' =>  23,
        'PT' =>  23,
        'RO' =>  19,
        'SK' =>  20,
        'SI' =>  22,
        'SE' =>  25,
    ];

    public function getByCountry(Country $country) : ValueAddedTax
    {
        return new ValueAddedTax(self::MEMORY[$country->getAlpha2Code()]);
    }
}
