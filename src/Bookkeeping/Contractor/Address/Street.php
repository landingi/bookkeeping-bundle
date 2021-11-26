<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;
use function trim;

final class Street
{
    private string $name;

    /**
     * @throws AddressException
     */
    public function __construct(string $name)
    {
        if (true === empty(trim($name))) {
            throw new AddressException('Street value cannot be an empty value!');
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
