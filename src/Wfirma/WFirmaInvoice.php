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
        $series = $invoice->with('series', '');
        $series->with('id', $this->invoiceSeries->getIdentifier()->toString());

        if ($this->language->isEnglish()) {
            $language = $invoice->with('translation_language', '');
            $language->with('id', '1');
        }

        $contents = $invoice->with('invoicecontents', '');

        foreach ($this->items->getAll() as $item) {
            $itemContent = $contents->with('invoicecontent', '');
            $itemContent->with('name', $item->getName()->toString());
            $itemContent->with('unit', 'szt.');
            $itemContent->with('count', $item->getUnits()->toString());
            $itemContent->with('price', $item->getPrice()->toString());
        }

        return $media;
    }
}
