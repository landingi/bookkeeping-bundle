<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Exception;

use Exception;
use Throwable;

class InvalidEmailAddressException extends Exception
{
    public static function invalidFormat(string $address, Throwable $previous = null): self
    {
        return new self("Given address: '$address' is invalid", 0, $previous);
    }

    public static function mailboxCannotBeNumber(string $address, Throwable $previous = null): self
    {
        return new self("Email mailbox (first part) cannot be number. Given address: '$address'", 0, $previous);
    }
}
