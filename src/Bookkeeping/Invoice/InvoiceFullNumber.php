<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

final class InvoiceFullNumber
{
    private string $fullNumber;

    public function __construct(string $fullNumber)
    {
        $this->fullNumber = $fullNumber;
    }

    public function toString(): string
    {
        return $this->fullNumber;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
