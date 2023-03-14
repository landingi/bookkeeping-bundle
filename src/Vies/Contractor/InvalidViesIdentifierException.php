<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Vies\Contractor;

use Exception;

final class InvalidViesIdentifierException extends Exception
{
    public static function validationFailed(string $identifier): self
    {
        return new self(sprintf("Identifier %s is invalid", $identifier));
    }
}