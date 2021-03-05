<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\City;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\PostalCode;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Street;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorAddress;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorBook;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorName;
use Landingi\BookkeepingBundle\Wfirma\Client\WfirmaClient;

final class WfirmaContractorBook implements ContractorBook
{
    private WfirmaClient $client;

    public function __construct(WfirmaClient $client)
    {
        $this->client = $client;
    }

    public function find(ContractorIdentifier $identifier): Contractor
    {
        return new Contractor\Person(
            $identifier,
            new ContractorName('name'),
            new ContractorAddress(
                new Street('name'),
                new PostalCode('postal'),
                new City('city'),
                new Country('poland', 'PL')
            )
        );
    }

    public function create(ContractorName $name, ContractorAddress $address): Contractor
    {
        return new Contractor\Person(
            new ContractorIdentifier('100'),
            $name,
            $address
        );
    }

    public function delete(ContractorIdentifier $identifier): void
    {
        $this->client->requestDELETE(sprintf('/contractors/delete/%s', $identifier->toString()));
    }
}
