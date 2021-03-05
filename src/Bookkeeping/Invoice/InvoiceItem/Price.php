<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Exception\InvoiceItemException;

final class Price
{
    private int $price;

    /**
     * @throws InvoiceItemException
     */
    public function __construct(int $price)
    {
        if ($price < 0) {
            throw new InvoiceItemException('Price must be a positive value!');
        }

        $this->price = $price;
    }

    public function toString(): string
    {
        return (string) $this->price;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
