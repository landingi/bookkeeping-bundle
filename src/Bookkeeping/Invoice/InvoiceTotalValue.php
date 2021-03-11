<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

final class InvoiceTotalValue
{
    private string $totalValue;

    public function __construct(string $totalValue)
    {
        $this->totalValue = $totalValue;
    }

    public function toString(): string
    {
        return $this->totalValue;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
