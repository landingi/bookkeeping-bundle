<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor;

final class ContractorEmail
{
    private string $email;

    public function __construct(string $email)
    {
        if (empty($email)) {
            throw new ContractorException('Contractor email cannot be an empty value!');
        }

        $this->email = $email;
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
