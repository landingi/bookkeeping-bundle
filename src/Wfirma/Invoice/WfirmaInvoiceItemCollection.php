<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Collection;

final class WfirmaInvoiceItemCollection extends Collection
{
    /**
     * @param WfirmaInvoiceItem[] $items
     */
    public function __construct(array $items)
    {
//        sprawdzic czy przyjmujemy WfirmaInvoiceItem w array
        parent::__construct($items);
    }

    /**
     * @return WfirmaInvoiceItem[]
     */
    public function getAll(): array
    {
        return $this->items;
    }
}
