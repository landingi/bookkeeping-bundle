<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

final class ContractorName
{
    private string $name;

    /**
     * @throws ContractorException
     */
    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new ContractorException('Contractor name cannot be an empty value!');
        }

        $this->name = $name;
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
