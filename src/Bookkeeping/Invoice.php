<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

use DateTime;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceDescription;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceFullNumber;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceNetPlnValue;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoicePaymentMethod;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceTotalValue;

abstract class Invoice
{
    protected InvoiceIdentifier $identifier;
    protected InvoiceSeries $invoiceSeries;
    protected InvoiceDescription $description;
    protected InvoiceFullNumber $fullNumber;
    protected InvoiceTotalValue $totalValue;
    protected InvoiceNetPlnValue $netPlnValue;
    protected Collection $items;
    protected Contractor $contractor;
    protected Currency $currency;
    protected DateTime $createdAt;
    protected DateTime $paidAt;
    protected DateTime $saleAt;
    protected Language $language;
    protected ?InvoicePaymentMethod $paymentMethod;

    public function __construct(
        InvoiceIdentifier $identifier,
        InvoiceSeries $invoiceSeries,
        InvoiceDescription $description,
        InvoiceFullNumber $fullNumber,
        InvoiceTotalValue $totalValue,
        InvoiceNetPlnValue $nettoPlnValue,
        Collection $items,
        Contractor $contractor,
        Currency $currency,
        DateTime $createdAt,
        DateTime $paidAt,
        DateTime $saleAt,
        Language $language,
        ?InvoicePaymentMethod $paymentMethod = null
    ) {
        $this->identifier = $identifier;
        $this->invoiceSeries = $invoiceSeries;
        $this->description = $description;
        $this->fullNumber = $fullNumber;
        $this->totalValue = $totalValue;
        $this->items = $items;
        $this->contractor = $contractor;
        $this->currency = $currency;
        $this->createdAt = $createdAt;
        $this->paidAt = $paidAt;
        $this->language = $language;
        $this->saleAt = $saleAt;
        $this->netPlnValue = $nettoPlnValue;
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * Returns NET sum of all invoice items. This is in-currency.
     * Note: If the invoice is a correction, the value is positive, as opposed to getTotalValue().
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

    public function getFullNumber(): InvoiceFullNumber
    {
        return $this->fullNumber;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getPaidAt(): DateTime
    {
        return $this->paidAt;
    }

    /**
     * Returns total invoice value, in-currency. This can be negative if the invoice is a correction.
     */
    public function getTotalValue(): InvoiceTotalValue
    {
        return $this->totalValue;
    }

    public function getNetPlnValue(): InvoiceNetPlnValue
    {
        return $this->netPlnValue;
    }

    public function getDescription(): InvoiceDescription
    {
        return $this->description;
    }

    public function getPaymentMethod(): InvoicePaymentMethod
    {
        return $this->paymentMethod;
    }

    abstract public function print(Media $media): Media;
}
