<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Fake;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Media;

class InvoiceItem extends Invoice\InvoiceItem
{
    public function print(Media $media): Media
    {
        return $media;
    }

    public static function createWithoutPrice(string $name): self
    {
        return new self(
            new Invoice\InvoiceItem\Name($name),
            new Invoice\InvoiceItem\Price(1),
            new Invoice\InvoiceItem\ValueAddedTax(23),
            new Invoice\InvoiceItem\NumberOfUnits(1)
        );
    }
}
