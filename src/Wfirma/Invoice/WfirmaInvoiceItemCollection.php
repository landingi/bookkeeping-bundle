<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice;

use Exception;
use Landingi\BookkeepingBundle\Bookkeeping\Collection;
use function array_filter;

final class WfirmaInvoiceItemCollection extends Collection
{
    /**
     * @param WfirmaInvoiceItem[] $items
     *
     * @throws Exception
     */
    public function __construct(array $items)
    {
        $validInvoiceItems = array_filter($items, static fn ($item) => $item instanceof WfirmaInvoiceItem);

        if (count($validInvoiceItems) !== count($items)) {
            throw new Exception();
        }

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
