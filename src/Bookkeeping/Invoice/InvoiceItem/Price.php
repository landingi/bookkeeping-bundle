<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Exception\InvoiceItemException;

final class Price
{
    private int $price;

    /**
     * Provide $price in cents. The minimal value is 0.
     *
     * @throws InvoiceItemException
     */
    public function __construct(int $price)
    {
        if ($price < 0) {
            throw new InvoiceItemException('Price must be a greater that zero');
        }

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
