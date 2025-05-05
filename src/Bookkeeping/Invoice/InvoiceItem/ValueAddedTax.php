<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem;

final class ValueAddedTax
{
    private float $rate;

    public function __construct(float $rate)
    {
        $this->rate = $rate;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}
