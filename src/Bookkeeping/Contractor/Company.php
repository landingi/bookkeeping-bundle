<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTaxIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Media;

final class Company implements Contractor
{
    private ContractorIdentifier $identifier;
    private ContractorName $name;
    private ContractorEmail $email;
    private ContractorAddress $address;
    private ValueAddedTaxIdentifier $valueAddedTaxIdentifier;

    public function __construct(
        ContractorIdentifier $identifier,
        ContractorName $name,
        ContractorEmail $email,
        ContractorAddress $address,
        ValueAddedTaxIdentifier $valueAddedTaxIdentifier
    ) {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->email = $email;
        $this->address = $address;
        $this->valueAddedTaxIdentifier = $valueAddedTaxIdentifier;
    }

    public function print(Media $media): Media
    {
        return $media;
    }

    public function getIdentifier(): ContractorIdentifier
    {
        return $this->identifier;
    }

    public function isEuropeanUnionCitizen(): bool
    {
        return true;
    }
}
