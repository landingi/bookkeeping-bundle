<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Media;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItem;

final class WfirmaInvoice extends Invoice
{
    public function print(Media $media): Media
    {
        $invoices = $media->with('invoices', '');
        $invoice = $invoices->with('invoice', '');
        $contractor = $invoice->with('contractor', '');
        $contractor->with('id', $this->contractor->getIdentifier()->toString());
        $invoice->with('paymentmethod', (string) $this->getPaymentMethod());
        $invoice->with('currency', $this->currency->getSymbol());
        $invoice->with('paid', '1');
        $invoice->with('alreadypaid_initial', (string) 0);
        $invoice->with('type', 'normal');
        $invoice->with('price_type', 'netto');
        $invoice->with('date', $this->createdAt->format('Y-m-d'));
        $invoice->with('paymentdate', $this->paidAt->format('Y-m-d'));
        $invoice->with('disposaldate', $this->saleAt->format('Y-m-d'));
        $invoice->with('description', $this->description->toString());
        $invoice->with('fullnumber', $this->fullNumber->toString());
        $invoice->with('total', $this->totalValue->toString());
        $series = $invoice->with('series', '');
        $series->with('id', $this->invoiceSeries->getIdentifier()->toString());

        if (!$this->language->isPolish()) {
            $language = $invoice->with('translation_language', '');
            $language->with('id', '1');
        }

        $contents = $invoice->with('invoicecontents', '');

        /**
         * @var WfirmaInvoiceItem $item
         */
        foreach ($this->items->getAll() as $item) {
            $item->print($contents);
        }

        /*
         * Landingi are Polish company - only european citizens are MOSS applicable.
         */
        if ($this->contractor->isEuropeanUnionCitizen() && !$this->contractor->isPolish()) {
            $vatDetails = $invoice->with('vat_moss_details', '');
            $vatDetails->with('type', 'SA');
            $vatDetails->with('evidence1_type', 'A');
            $vatDetails->with('evidence2_type', 'F');
        }

        return $media;
    }
}
