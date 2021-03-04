<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;

final class Country
{
    private string $name;
    private string $alpha2Code;

    /**
     * @throws AddressException
     */
    public function __construct(string $name, string $alpha2Code)
    {
        if (empty($name)) {
            throw new AddressException('Country name cannot be an empty value!');
        }

        if (2 !== strlen($alpha2Code)) {
            throw new AddressException('Country code must be a 2 character string!');
        }

        $this->name = $name;
        $this->alpha2Code = $alpha2Code;
    }

    public function getAlpha2Code(): string
    {
        return $this->alpha2Code;
    }

    public function getName(): string
    {
        return $this->name;
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
