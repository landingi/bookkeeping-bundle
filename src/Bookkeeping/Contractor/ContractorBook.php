<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\InvalidVatIdException;

interface ContractorBook
{
    public function find(ContractorIdentifier $identifier): Contractor;

    /**
     * @throws InvalidVatIdException
     * @throws ContractorException
     */
    public function create(Contractor $contractor): Contractor;
    public function delete(ContractorIdentifier $identifier): void;

    /**
     * @throws InvalidVatIdException
     * @throws ContractorException
     */
    public function update(Contractor $contractor): Contractor;
}
