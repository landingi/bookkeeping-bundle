<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Vies\Contractor\Company\ValueAddedTax;

use Exception;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\IdentifierFactory;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\SimpleIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\ValidatedIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTaxIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorException;
use Landingi\BookkeepingBundle\Vies\Client\ViesClient;
use Landingi\BookkeepingBundle\Vies\Contractor\InvalidViesIdentifierException;

final class ViesIdentifierFactory implements IdentifierFactory
{
    private ViesClient $viesClient;

    public function __construct(ViesClient $viesClient)
    {
        $this->viesClient = $viesClient;
    }

    /**
     * @throws InvalidViesIdentifierException
     */
    public function create(string $identifier, string $country): ValueAddedTaxIdentifier
    {
        try {
            $validation = $this->viesClient->validateVat($country, $identifier);

            if (false === $validation['isValid']) {
                throw InvalidViesIdentifierException::validationFailed($identifier);
            }

            $country = new Country($country);

            if ($country->isPoland()) {
                return new SimpleIdentifier($identifier);
            }

            return new ValidatedIdentifier(new SimpleIdentifier($identifier), $country);
        } catch (ContractorException|Exception $e) {
            throw InvalidViesIdentifierException::validationFailed($identifier);
        }
    }
}
