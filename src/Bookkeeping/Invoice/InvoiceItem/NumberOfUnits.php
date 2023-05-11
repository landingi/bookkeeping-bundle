<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Exception\InvoiceItemException;

final class NumberOfUnits
{
    private int $number;

    /**
     * Note: If the invoice is a correction, the number of units of an invoice item can be zero.
     *
     * @throws InvoiceItemException
     */
    public function __construct(int $number)
    {
        if ($number < 0) {
            throw new InvoiceItemException('The number of units must not be less than 0');
        }

        $this->number = $number;
    }

    public function toString(): string
    {
        return (string) $this->number;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toInteger(): int
    {
        return $this->number;
    }
}
