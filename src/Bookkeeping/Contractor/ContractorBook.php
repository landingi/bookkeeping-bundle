<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor;

interface ContractorBook
{
    public function find(ContractorIdentifier $identifier): Contractor;
    public function create(ContractorName $name, ContractorAddress $address): Contractor;
    public function delete(ContractorIdentifier $identifier): void;
}
