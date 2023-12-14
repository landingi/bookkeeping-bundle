<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

use DateTimeImmutable;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceFullNumber;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceNetPlnValue;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoicePaymentMethod;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceTotalValue;

final class InvoiceSummary
{
    private InvoiceIdentifier $identifier;
    private InvoiceFullNumber $fullNumber;
    private InvoiceTotalValue $totalValue;
    private InvoiceNetPlnValue $netPlnValue;
    private Currency $currency;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $modifiedAt;
    private DateTimeImmutable $paidAt;
    private DateTimeImmutable $disposalDate;
    private InvoicePaymentMethod $paymentMethod;

    public function __construct(
        InvoiceIdentifier $identifier,
        InvoiceFullNumber $fullNumber,
        InvoiceTotalValue $totalValue,
        InvoiceNetPlnValue $netPlnValue,
        Currency $currency,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $modifiedAt,
        DateTimeImmutable $paidAt,
        DateTimeImmutable $disposalDate,
        InvoicePaymentMethod $paymentMethod
    ) {
        $this->identifier = $identifier;
        $this->fullNumber = $fullNumber;
        $this->totalValue = $totalValue;
        $this->netPlnValue = $netPlnValue;
        $this->currency = $currency;
        $this->createdAt = $createdAt;
        $this->modifiedAt = $modifiedAt;
        $this->paidAt = $paidAt;
        $this->disposalDate = $disposalDate;
        $this->paymentMethod = $paymentMethod;
    }

    public function getIdentifier(): InvoiceIdentifier
    {
        return $this->identifier;
    }

    public function getFullNumber(): InvoiceFullNumber
    {
        return $this->fullNumber;
    }

    public function getNetPlnValue(): InvoiceNetPlnValue
    {
        return $this->netPlnValue;
    }

    public function getTotalValue(): InvoiceTotalValue
    {
        return $this->totalValue;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getModifiedAt(): DateTimeImmutable
    {
        return $this->modifiedAt;
    }

    public function getPaidAt(): DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function getDisposalDate(): DateTimeImmutable
    {
        return $this->disposalDate;
    }

    public function getPaymentMethod(): InvoicePaymentMethod
    {
        return $this->paymentMethod;
    }
}
