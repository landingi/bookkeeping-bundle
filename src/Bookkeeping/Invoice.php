<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

abstract class Invoice
{
    protected Invoice\InvoiceIdentifier $identifier;
    protected Invoice\InvoiceSeries $invoiceSeries;
    protected Invoice\InvoiceDescription $description;
    protected Invoice\InvoiceItemCollection $items;
    protected Contractor $contractor;

    public function __construct(
        Invoice\InvoiceIdentifier $identifier,
        Invoice\InvoiceSeries $invoiceSeries,
        Invoice\InvoiceDescription $description,
        Invoice\InvoiceItemCollection $items,
        Contractor $contractor
    ) {
        $this->identifier = $identifier;
        $this->invoiceSeries = $invoiceSeries;
        $this->description = $description;
        $this->items = $items;
        $this->contractor = $contractor;
    }

    abstract public function print(Media $media): Media;
}
