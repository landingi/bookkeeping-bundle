<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Vies\Contractor\Company\ValueAddedTax;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\IdentifierFactory;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\SimpleIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\ValidatedIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTaxIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorException;
use Landingi\BookkeepingBundle\Vies\Client\ViesClient;
use Landingi\BookkeepingBundle\Vies\Contractor\ConcurrentRequestViesIdentifierException;
use Landingi\BookkeepingBundle\Vies\Contractor\InvalidViesIdentifierException;

final class ViesIdentifierFactory implements IdentifierFactory
{
    private const CONCURRENT_REQUESTS_ERROR_NAME = 'MS_MAX_CONCURRENT_REQ';
    private ViesClient $viesClient;

    public function __construct(ViesClient $viesClient)
    {
        $this->viesClient = $viesClient;
    }

    /**
     * @throws InvalidViesIdentifierException|ConcurrentRequestViesIdentifierException
     */
    public function create(string $identifier, string $country): ValueAddedTaxIdentifier
    {
        try {
            $country = new Country($country);

            $this->validateVat($identifier, $country);

            if ($country->isPoland() || !$country->isEuropeanUnion()) {
                return new SimpleIdentifier($identifier);
            }

            return new ValidatedIdentifier(new SimpleIdentifier($identifier), $country);
        } catch (ContractorException $e) {
            throw InvalidViesIdentifierException::validationFailed($identifier);
        }
    }

    /**
     * @throws ConcurrentRequestViesIdentifierException
     * @throws InvalidViesIdentifierException
     */
    private function validateVat(string $identifier, Country $country): void
    {
        if ($country->isEuropeanUnion()) {
            $validation = $this->viesClient->validateVat($country->toString(), $identifier);

            if (self::CONCURRENT_REQUESTS_ERROR_NAME === $validation['userError']) {
                throw ConcurrentRequestViesIdentifierException::validationBlockedByConcurrentRequests($identifier);
            }

            if (false === $validation['isValid']) {
                throw InvalidViesIdentifierException::validationFailed($identifier);
            }
        }
    }
}
