<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice;

interface InvoiceBook
{
    public function find(InvoiceIdentifier $identifier): Invoice;
    public function create(
        Contractor $contractor,
        InvoiceSeries $series,
        InvoiceDescription $description,
        InvoiceItemCollection $itemCollection
    ): Invoice;
    public function delete(InvoiceIdentifier $identifier): void;
}
