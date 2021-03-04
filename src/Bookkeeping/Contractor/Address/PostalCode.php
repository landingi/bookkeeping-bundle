<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;

final class PostalCode
{
    private string $code;

    /**
     * @throws AddressException
     */
    public function __construct(string $code)
    {
        if (empty($code)) {
            throw new AddressException('Postal code cannot be an empty value!');
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
