<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;

final class InvoiceSeriesIdentifier
{
    private int $identifier;

    public function __construct(int $identifier)
    {
        $this->identifier = $identifier;
    }

    public function toString(): string
    {
        return (string) $this->identifier;
    }

    public function toInteger(): int
    {
        return $this->identifier;
    }
}
