<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

final class InvoiceTotalValue
{
    private int $totalValue;

    /**
     * Provide $totalValue in "cents". This is the value in the specified currency.
     * This can be negative if the invoice is a correction.
     * If this is a corrected invoice, this should be the value before corrections.
     *
     * @throws InvoiceException
     */
    public function __construct(int $totalValue)
    {
        if (0 === $totalValue) {
            throw new InvoiceException('Total Value cannot be zero');
        }

        $this->totalValue = $totalValue;
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
        return $this->totalValue / 100;
    }
}
