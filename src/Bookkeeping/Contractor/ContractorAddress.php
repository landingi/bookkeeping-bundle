<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;

final class ContractorAddress
{
    private Street $street;
    private PostalCode $postalCode;
    private City $city;
    private Country $country;

    public function __construct(Street $street, PostalCode $postalCode, City $city, Country $country)
    {
        $this->street = $street;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->country = $country;
    }

    public function toString(): string
    {
        return <<<STRING
{$this->street}
{$this->postalCode}, {$this->city}
{$this->country}
STRING;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
