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

        return $media;
    }
}
