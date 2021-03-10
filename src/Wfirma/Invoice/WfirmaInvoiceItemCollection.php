<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Collection;
use Landingi\BookkeepingBundle\Wfirma\WfirmaException;
use function array_filter;
use function count;

final class WfirmaInvoiceItemCollection extends Collection
{
    /**
     * @param WfirmaInvoiceItem[] $items
     *
     * @throws WfirmaException
     */
    public function __construct(array $items)
    {
        $validInvoiceItems = array_filter($items, static fn ($item) => $item instanceof WfirmaInvoiceItem);

        if (count($validInvoiceItems) !== count($items)) {
            throw new WfirmaException('Invalid Wfirma item collection');
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
