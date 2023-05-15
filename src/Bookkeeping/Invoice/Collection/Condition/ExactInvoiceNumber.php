<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\InvoiceCondition;

final class ExactInvoiceNumber implements InvoiceCondition
{
    private string $invoiceNumber;

    /**
     * This accepts an invoice's full number to compare against.
     */
    public function __construct(string $invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function __toString(): string
    {
        return $this->invoiceNumber;
    }
}
