<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

final class InvoiceNetPlnValue
{
    private int $netPlnValue;

    /**
     * Provide $netPlnValue in "cents". This is returned in PLN.
     * This can be negative if the invoice is a correction.
     *
     * @throws InvoiceException
     */
    public function __construct(int $netPlnValue)
    {
        if (0 === $netPlnValue) {
            throw new InvoiceException('Netto PLN Value cannot be zero');
        }

        $this->netPlnValue = $netPlnValue;
    }

    public function toString(): string
    {
        return (string) $this->toFloat();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toFloat(): float
    {
        return $this->netPlnValue / 100;
    }
}
