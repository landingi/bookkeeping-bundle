<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception\InvalidEmailAddressException;

final class ContractorEmail
{
    private string $address;

    /**
     * Email validation from landingi/core.
     *
     * @throws InvalidEmailAddressException
     */
    public function __construct(string $address)
    {
        if (false === filter_var($address, FILTER_VALIDATE_EMAIL)) {
            throw InvalidEmailAddressException::invalidFormat($address);
        }

        if (is_numeric(explode('@', $address)[0])) {
            throw InvalidEmailAddressException::mailboxCannotBeNumber($address);
        }

        $this->address = $address;
    }

    public function toString(): string
    {
        return $this->address;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
