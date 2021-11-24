<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTaxIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorException;

/**
 * The class represents NOT validated tax identifier.
 */
final class SimpleIdentifier implements ValueAddedTaxIdentifier
{
    private string $identifier;

    /**
     * @throws ContractorException
     */
    public function __construct(string $identifier)
    {
        if (empty($identifier)) {
            throw new ContractorException('Value added tax identifier cannot be an empty value!');
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
