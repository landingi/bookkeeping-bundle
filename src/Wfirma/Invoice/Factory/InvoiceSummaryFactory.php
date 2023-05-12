<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice\Factory;

use DateTimeImmutable;
use Landingi\BookkeepingBundle\Bookkeeping\Currency;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceFullNumber;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceNetPlnValue;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoicePaymentMethod;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceTotalValue;
use Landingi\BookkeepingBundle\Bookkeeping\InvoiceSummary;

final class InvoiceSummaryFactory
{
    public function getSummaryFromApiData(array $data): InvoiceSummary
    {
        return new InvoiceSummary(
            new InvoiceIdentifier($data['id']),
            new InvoiceFullNumber($data['fullnumber']),
            new InvoiceTotalValue((int) ($data['total'] * 100)),
            new InvoiceNetPlnValue((int) ($data['netto'] * 100)),
            new Currency($data['currency']),
            new DateTimeImmutable($data['created']),
            new DateTimeImmutable($data['modified']),
            new DateTimeImmutable($data['paymentdate']),
            new InvoicePaymentMethod($data['paymentmethod'])
        );
    }
}
