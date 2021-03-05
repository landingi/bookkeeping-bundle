<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

use DateTime;

abstract class Invoice
{
    protected Invoice\InvoiceIdentifier $identifier;
    protected Invoice\InvoiceSeries $invoiceSeries;
    protected Invoice\InvoiceDescription $description;
    protected Invoice\InvoiceItemCollection $items;
    protected Contractor $contractor;
    protected Currency $currency;
    protected DateTime $createdAt;
    protected DateTime $paidAt;

    public function __construct(
        Invoice\InvoiceIdentifier $identifier,
        Invoice\InvoiceSeries $invoiceSeries,
        Invoice\InvoiceDescription $description,
        Invoice\InvoiceItemCollection $items,
        Contractor $contractor,
        Currency $currency,
        DateTime $createdAt,
        DateTime $paidAt
    ) {
        $this->identifier = $identifier;
        $this->invoiceSeries = $invoiceSeries;
        $this->description = $description;
        $this->items = $items;
        $this->contractor = $contractor;
        $this->currency = $currency;
        $this->createdAt = $createdAt;
        $this->paidAt = $paidAt;
    }

    /**
     * Returns NET sum of all invoice items.
     */
    public function getMoneyValue(): int
    {
        $sum = 0;

        foreach ($this->items->getAll() as $item) {
            $sum += $item->getPrice()->toInteger();
        }

        return $sum;
    }

    abstract public function print(Media $media): Media;
}
