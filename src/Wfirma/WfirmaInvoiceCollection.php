<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma;

use Landingi\BookkeepingBundle\Bookkeeping\Collection;

final class WfirmaInvoiceCollection extends Collection
{
    /**
     * @param WfirmaInvoice[] $invoices
     *
     * @throws WfirmaException
     */
    public function __construct(array $invoices)
    {
        $validInvoices = array_filter($invoices, static fn ($item) => $item instanceof WfirmaInvoice);

        if (count($validInvoices) !== count($invoices)) {
            throw new WfirmaException('Invalid Wfirma invoice collection');
        }

        parent::__construct($invoices);
    }

    /**
     * @return WfirmaInvoice[]
     */
    public function getAll(): array
    {
        return $this->items;
    }
}
