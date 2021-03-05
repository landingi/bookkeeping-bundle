<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Name;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\NumberOfUnits;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Price;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\ValueAddedTax;
use Landingi\BookkeepingBundle\Bookkeeping\Media;

abstract class InvoiceItem
{
    protected Name $name;
    protected Price $price;
    protected ValueAddedTax $tax;
    protected NumberOfUnits $numberOfUnits;

    public function __construct(Name $name, Price $price, ValueAddedTax $tax, NumberOfUnits $numberOfUnits)
    {
        $this->name = $name;
        $this->price = $price;
        $this->tax = $tax;
        $this->numberOfUnits = $numberOfUnits;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    abstract public function print(Media $media): Media;
}
