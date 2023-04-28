<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Collection;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition;

interface InvoiceBook
{
    public function find(InvoiceIdentifier $identifier): Invoice;
    public function list(int $page, Condition ...$conditions): Collection;
    public function create(Invoice $invoice): Invoice;
    public function delete(InvoiceIdentifier $identifier): void;
    public function download(InvoiceIdentifier $identifier): string;
}
