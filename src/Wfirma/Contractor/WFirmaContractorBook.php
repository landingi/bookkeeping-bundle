<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorBook;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorIdentifier;

final class WFirmaContractorBook implements ContractorBook
{

    public function find(ContractorIdentifier $identifier): Contractor
    {
        // TODO: Implement find() method.
    }

    public function create(): Contractor
    {
        // TODO: Implement create() method.
    }

    public function delete(): void
    {
        // TODO: Implement delete() method.
    }
}