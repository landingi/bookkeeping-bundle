<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Media;

final class Person implements Contractor
{
    private ContractorIdentifier $identifier;
    private ContractorName $name;
    private ContractorAddress $address;

    public function __construct(ContractorIdentifier $identifier, ContractorName $name, ContractorAddress $address)
    {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->address = $address;
    }

    public function print(Media $media): Media
    {
        return $media;
    }

    public function getIdentifier(): ContractorIdentifier
    {
        return $this->identifier;
    }
}
