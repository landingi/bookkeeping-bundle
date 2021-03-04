<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

use ArrayIterator;
use IteratorAggregate;

final class InvoiceItemCollection implements IteratorAggregate
{
    /**
     * @var InvoiceItem[]
     */
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }
}
