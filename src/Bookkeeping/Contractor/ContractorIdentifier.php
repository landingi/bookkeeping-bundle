<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

use function trim;

final class ContractorIdentifier
{
    private string $identifier;

    /**
     * @throws ContractorException
     */
    public function __construct(string $identifier)
    {
        if (true === empty(trim($identifier))) {
            throw new ContractorException('Identifier value cannot be an empty value!');
        }

        $this->identifier = $identifier;
    }

    public function toString(): string
    {
        return $this->identifier;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
