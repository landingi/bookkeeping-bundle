<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Vies\Contractor\Company\ValueAddedTax;

use DragonBe\Vies\Vies;
use Exception;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address\Country;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\IdentifierFactory;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\SimpleIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTax\ValidatedIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Company\ValueAddedTaxIdentifier;
use Landingi\BookkeepingBundle\Vies\ViesException;

final class ViesIdentifierFactory implements IdentifierFactory
{
    private Vies $vies;

    public function __construct(Vies $vies)
    {
        $this->vies = $vies;
    }

    /**
     * @throws \Landingi\BookkeepingBundle\Vies\ViesException
     */
    public function create(string $identifier, string $country): ValueAddedTaxIdentifier
    {
        try {
            $validation = $this->vies->validateVat($country, $identifier);

            if (false === $validation->isValid()) {
                throw new Exception('Invalid Tax Id');
            }
        } catch (Exception $e) {
            throw new ViesException('VIES external service exception: ' . $e->getMessage());
        }

        if ($validation->isValid()) {
            $country = new Country($country);

            if ($country->isPoland()) {
                return new SimpleIdentifier($identifier);
            }

            return new ValidatedIdentifier(new SimpleIdentifier($identifier), $country);
        }

        return new SimpleIdentifier($identifier);
    }
}
