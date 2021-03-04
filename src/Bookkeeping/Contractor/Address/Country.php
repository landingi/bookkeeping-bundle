<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;

final class Country
{
    private string $code;

    /**
     * @throws AddressException
     */
    public function __construct(string $code)
    {
        if (2 !== strlen($code)) {
            throw new AddressException('Country code must be a 2 character string!');
        }

        $this->code = $code;
    }

    public function toString(): string
    {
        return $this->code;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
