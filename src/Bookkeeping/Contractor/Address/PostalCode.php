<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\AddressException;
use function trim;

final class PostalCode
{
    private string $code;

    /**
     * @throws AddressException
     */
    public function __construct(string $code)
    {
        if (true === empty(trim($code))) {
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
