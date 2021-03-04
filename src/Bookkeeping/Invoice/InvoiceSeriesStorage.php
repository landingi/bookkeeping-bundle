<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

interface InvoiceSeriesStorage
{
    public function getByIdentifier(InvoiceIdentifier $identifier): InvoiceSeries;
    public function getByName(string $name): InvoiceSeries;
}
