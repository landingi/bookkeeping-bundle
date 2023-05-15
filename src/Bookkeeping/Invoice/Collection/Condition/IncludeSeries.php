<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\InvoiceCondition;

final class IncludeSeries implements InvoiceCondition
{
    private string $invoiceNumberFragment;

    /**
     * This accepts a fragment of the invoice's full number, to compare against
     */
    public function __construct(string $invoiceNumberFragment)
    {
        $this->invoiceNumberFragment = $invoiceNumberFragment;
    }

    public function __toString(): string
    {
        return $this->invoiceNumberFragment;
    }
}
