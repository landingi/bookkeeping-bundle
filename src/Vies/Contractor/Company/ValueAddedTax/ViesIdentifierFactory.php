<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Vies\Contractor\Company\ValueAddedTax;

use DragonBe\Vies\Vies;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\IdentifierFactory;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\SimpleIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\ValidatedIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTaxIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\ContractorException;
use Landingi\BookkeepingBundle\Vies\Contractor\InvalidViesIdentifierException;
use Landingi\BookkeepingBundle\Vies\Exception\ViesServiceException;
use Landingi\BookkeepingBundle\Vies\ViesException;

final class ViesIdentifierFactory implements IdentifierFactory
{
    private Vies $vies;

    public function __construct(Vies $vies)
    {
        $this->vies = $vies;
    }

    /**
     * @throws ViesException
     * @throws InvalidViesIdentifierException
     */
    public function create(string $identifier, string $country): ValueAddedTaxIdentifier
    {
        try {
            $validation = $this->vies->validateVat($country, $identifier);

            if (false === $validation->isValid()) {
                throw InvalidViesIdentifierException::validationFailed($identifier);
            }

            $country = new Country($country);

            if ($country->isPoland()) {
                return new SimpleIdentifier($identifier);
            }

            return new ValidatedIdentifier(new SimpleIdentifier($identifier), $country);
        } catch (ContractorException $e) {
            throw InvalidViesIdentifierException::validationFailed($identifier);
        } catch (\DragonBe\Vies\ViesException $e) {
            throw new ViesException($e->getMessage(), $e->getCode(), $e);
        } catch (\DragonBe\Vies\ViesServiceException $e) {
            throw new ViesServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
