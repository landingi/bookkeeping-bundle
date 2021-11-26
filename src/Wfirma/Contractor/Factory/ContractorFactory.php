<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Contractor\Factory;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\IdentifierFactory;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorAddress;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\InvalidEmailAddressException;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Person;

final class ContractorFactory
{
    private IdentifierFactory $identifierFactory;

    public function __construct(IdentifierFactory $identifierFactory)
    {
        $this->identifierFactory = $identifierFactory;
    }

    /**
     * @throws ContractorException
     * @throws InvalidEmailAddressException
     */
    public function getContractor(array $data): Contractor
    {
        if (empty($data['nip'])) {
            return new Person(
                new Contractor\ContractorIdentifier((string) $data['id']),
                new ContractorName((string) $data['name']),
                new Contractor\ContractorEmail((string) $data['email']),
                $this->getContractorAddress($data)
            );
        }

        return new Company(
            new Contractor\ContractorIdentifier((string) $data['id']),
            new Contractor\ContractorName((string) $data['name']),
            new Contractor\ContractorEmail((string) $data['email']),
            $this->getContractorAddress($data),
            $this->identifierFactory->create($this->trimCountryFromValueAddedTaxIdentifier($data), (string) $data['country'])
        );
    }

    /**
     * @throws AddressException
     */
    private function getContractorAddress(array $data): ContractorAddress
    {
        return new ContractorAddress(
            new Street((string) $data['street']),
            new PostalCode((string) $data['zip']),
            new City((string) $data['city']),
            new Country((string) $data['country'])
        );
    }

    /**
     * $data['nip'] example PL6482791634, and we want to extract only the numeric value.
     */
    private function trimCountryFromValueAddedTaxIdentifier(array $data): string
    {
        return \str_replace((string) $data['country'], '', (string) $data['nip']);
    }
}
