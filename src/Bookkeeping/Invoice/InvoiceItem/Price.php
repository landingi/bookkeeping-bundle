<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Exception\InvoiceItemException;

final class Price
{
    private int $price;

    /**
     * Provide $price in cents. The minimal value is 1 (0.01).
     */
    public function __construct(int $price)
    {
        $this->price = $price;
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
        return $this->price / 100;
    }
}
