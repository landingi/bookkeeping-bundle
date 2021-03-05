<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorBook;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;
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
        // TODO: Implement find() method.
    }

    public function create(): Contractor
    {
        // TODO: Implement create() method.
    }

    public function delete(ContractorIdentifier $identifier): void
    {
        $this->client->requestDELETE(sprintf('/contractors/delete/%s', $identifier->toString()));
    }
}
