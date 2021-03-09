<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

use DateTime;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem;

abstract class Invoice
{
    protected Invoice\InvoiceIdentifier $identifier;
    protected Invoice\InvoiceSeries $invoiceSeries;
    protected Invoice\InvoiceDescription $description;
    protected Collection $items;
    protected Contractor $contractor;
    protected Currency $currency;
    protected DateTime $createdAt;
    protected DateTime $paidAt;
    protected Language $language;

    public function __construct(
        Invoice\InvoiceIdentifier $identifier,
        Invoice\InvoiceSeries $invoiceSeries,
        Invoice\InvoiceDescription $description,
        Collection $items,
        Contractor $contractor,
        Currency $currency,
        DateTime $createdAt,
        DateTime $paidAt,
        Language $language
    ) {
        $this->identifier = $identifier;
        $this->invoiceSeries = $invoiceSeries;
        $this->description = $description;
        $this->items = $items;
        $this->contractor = $contractor;
        $this->currency = $currency;
        $this->createdAt = $createdAt;
        $this->paidAt = $paidAt;
        $this->language = $language;
    }

    /**
     * Returns NET sum of all invoice items.
     */
    public function getMoneyValue(): float
    {
        $sum = 0;

        /** @var InvoiceItem $item */
        foreach ($this->items->getAll() as $item) {
            $sum += $item->getPrice()->toFloat() * $item->getUnits()->toInteger();
        }

        return $sum;
    }

    public function getIdentifier(): InvoiceIdentifier
    {
        return $this->identifier;
    }

    abstract public function print(Media $media): Media;
}
