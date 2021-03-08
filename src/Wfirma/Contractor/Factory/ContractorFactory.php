<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Contractor\Factory;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorAddress;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;

final class ContractorFactory
{
    /**
     * @throws ContractorException
     */
    public function getContractor(array $data): Contractor
    {
        if (empty($data['nip'])) {
            return new Contractor\Person(
                new Contractor\ContractorIdentifier($data['id']),
                new ContractorName($data['name']),
                new Contractor\ContractorEmail($data['email']),
                $this->getContractorAddress($data)
            );
        }

        return new Company(
            new Contractor\ContractorIdentifier($data['id']),
            new ContractorName($data['name']),
            new Contractor\ContractorEmail($data['email']),
            $this->getContractorAddress($data),
            new Company\ValueAddedTaxIdentifier($data['nip'])
        );
    }

    /**
     * @throws AddressException
     */
    private function getContractorAddress(array $data): ContractorAddress
    {
        return new ContractorAddress(
            new Street($data['street']),
            new PostalCode($data['zip']),
            new City($data['city']),
            new Country($data['country'])
        );
    }
}