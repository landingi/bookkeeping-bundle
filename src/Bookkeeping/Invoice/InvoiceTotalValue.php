<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

final class InvoiceTotalValue
{
    private int $totalValue;

    /**
     * Provide $totalValue in cents. The minimal value is 1 (0.01).
     *
     * @throws InvoiceException
     */
    public function __construct(int $totalValue)
    {
        if ($totalValue <= 0) {
            throw new InvoiceException('Total Value must be greater that zero');
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
