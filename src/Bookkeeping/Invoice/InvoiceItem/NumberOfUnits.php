<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Exception\InvoiceItemException;

final class NumberOfUnits
{
    private int $number;

    /**
     * @throws InvoiceItemException
     */
    public function __construct(int $number)
    {
        if ($number < 1) {
            throw new InvoiceItemException('The number of units must not be less than 1');
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
}
