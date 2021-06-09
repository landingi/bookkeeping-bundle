<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Media;

final class Person implements Contractor
{
    private ContractorIdentifier $identifier;
    private ContractorName $name;
    private ContractorEmail $email;
    private ContractorAddress $address;

    public function __construct(
        ContractorIdentifier $identifier,
        ContractorName $name,
        ContractorEmail $email,
        ContractorAddress $address
    ) {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->email = $email;
        $this->address = $address;
    }

    public function print(Media $media): Media
    {
        $contractors = $media->with('contractors', '');
        $contractor = $contractors->with('contractor', '');
        $contractor->with('name', $this->name->toString());
        $contractor->with('altname', $this->name->toString());
        $contractor->with('street', $this->address->getStreet()->toString());
        $contractor->with('zip', $this->address->getPostalCode()->toString());
        $contractor->with('city', $this->address->getCity()->toString());
        $contractor->with('country', $this->address->getCountry()->toString());
        $contractor->with('email', $this->email->toString());

        return $media;
    }

    public function isPolish(): bool
    {
        return $this->address->getCountry()->isPoland();
    }

    public function getIdentifier(): ContractorIdentifier
    {
        return $this->identifier;
    }

    public function isEuropeanUnionCitizen(): bool
    {
        return $this->address->getCountry()->isEuropeanUnion();
    }

    public function isEuropeanUnionCompany(): bool
    {
        return false;
    }
}
