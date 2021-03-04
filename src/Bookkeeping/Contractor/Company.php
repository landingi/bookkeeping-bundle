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
    private ContractorAddress $address;
    private ValueAddedTaxIdentifier $valueAddedTaxIdentifier;

    public function __construct(
        ContractorIdentifier $identifier,
        ContractorName $name,
        ContractorAddress $address,
        ValueAddedTaxIdentifier $valueAddedTaxIdentifier
    ) {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->address = $address;
        $this->valueAddedTaxIdentifier = $valueAddedTaxIdentifier;
    }

    public function toString()
    {
        return <<<STRING
{$this->name}
{$this->address}
{$this->valueAddedTaxIdentifier}
STRING;
    }

    public function print(Media $media): Media
    {
        // TODO: Implement print() method.
    }
}