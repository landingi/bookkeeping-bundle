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
        $contractors = $media->with('contractors', '');
        $contractor = $contractors->with('contractor', '');
        $contractor->with('name', $this->name->toString());
        $contractor->with('altname', $this->name->toString());
        $contractor->with('street', $this->address->getStreet()->toString());
        $contractor->with('zip', $this->address->getPostalCode()->toString());
        $contractor->with('city', $this->address->getCity()->toString());
        $contractor->with('country', $this->address->getCountry()->getAlpha2Code());
        $contractor->with('email', $this->email->toString());

        if ($this->address->getCountry()->isPoland()) {
            $contractor->with('tax_id_type', 'nip');
            $contractor->with('nip', $this->valueAddedTaxIdentifier->toString());
        } elseif ($this->address->getCountry()->isEuropeanUnion()) {
            $contractor->with('tax_id_type', 'vat');
            $contractor->with('nip', $this->valueAddedTaxIdentifier->toString());
        } else {
            $contractor->with('tax_id_type', 'custom');
            $contractor->with('nip', $this->valueAddedTaxIdentifier->toString());
        }

        return $media;
    }

    public function getIdentifier(): ContractorIdentifier
    {
        return $this->identifier;
    }

    public function getEmail(): ContractorEmail
    {
        return $this->email;
    }

    public function changeEmail(ContractorEmail $email): void
    {
        $this->email = $email;
    }

    public function isPolish(): bool
    {
        return $this->address->getCountry()->isPoland();
    }

    public function isEuropeanUnionCitizen(): bool
    {
        return false;
    }

    public function isEuropeanUnionCompany(): bool
    {
        return $this->address->getCountry()->isEuropeanUnion();
    }

    public function changeValueAddedTaxIdentifier(ValueAddedTaxIdentifier $identifier): void
    {
        $this->valueAddedTaxIdentifier = $identifier;
    }
}
