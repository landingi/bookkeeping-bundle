<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Vies\Contractor;

use Exception;

final class ConcurrentRequestViesIdentifierException extends Exception
{
    public static function validationBlockedByConcurrentRequests(string $identifier): self
    {
        return new self(sprintf('Validation for identifier %s was blocked', $identifier));
    }
}
