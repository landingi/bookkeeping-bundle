<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Media;

final class WFirmaInvoice extends Invoice
{
    public function print(Media $media): Media
    {
        $invoices = $media->with('invoices', '');
        $invoice = $invoices->with('invoice', '');
        $contractor = $invoice->with('contractor', '');
        $contractor->with('id', $this->contractor->getIdentifier()->toString());
        $invoice->with('paymentmethod', 'transfer');
        $invoice->with('currency', $this->currency->getSymbol());
        $invoice->with('alreadypaid_initial', (string) $this->getMoneyValue());
        $invoice->with('type', 'normal');
        $invoice->with('date', $this->createdAt->format('Y-m-d'));
        $invoice->with('paymentdate', $this->paidAt->format('Y-m-d'));
        $invoice->with('description', $this->description->toString());

        return $media;
    }
}
