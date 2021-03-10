<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;

final class City
{
    private string $name;

    /**
     * @throws AddressException
     */
    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new AddressException('City name cannot be an empty value!');
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
