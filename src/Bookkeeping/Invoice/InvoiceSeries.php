<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries\InvoiceSeriesIdentifier;

final class InvoiceSeries
{
    private InvoiceSeriesIdentifier $identifier;

    public function __construct(InvoiceSeriesIdentifier $identifier)
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): InvoiceSeriesIdentifier
    {
        return $this->identifier;
    }
}
