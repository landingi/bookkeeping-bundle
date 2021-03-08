<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;

interface InvoiceSeriesStorage
{
    public function getByIdentifier(InvoiceIdentifier $identifier): InvoiceSeries;
    public function getByName(string $name): InvoiceSeries;
}
