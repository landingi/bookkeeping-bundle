<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition;

final class IncludeSeries implements Condition
{
    private string $invoiceSeries;

    public function __construct(string $invoiceSeries)
    {
        $this->invoiceSeries = $invoiceSeries;
    }

    public function __toString(): string
    {
        return $this->invoiceSeries;
    }
}
