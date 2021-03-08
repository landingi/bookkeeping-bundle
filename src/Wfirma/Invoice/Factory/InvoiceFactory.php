<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice\Factory;

use DateTime;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Currency;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceDescription;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItemCollection;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries\InvoiceSeriesIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Language;
use Landingi\BookkeepingBundle\Wfirma\WFirmaInvoice;
use Landingi\BookkeepingBundle\Wfirma\WFirmaInvoiceItem;

final class InvoiceFactory
{
    public function getInvoiceFromApiData(array $data, Contractor $contractor): Invoice
    {
        return new WfirmaInvoice(
            new Invoice\InvoiceIdentifier($data['id']),
            new InvoiceSeries(new InvoiceSeriesIdentifier($data['series']['id'])),
            new InvoiceDescription($data['description']),
            new InvoiceItemCollection($this->getInvoiceItems($data['invoicecontents'])),
            $contractor,
            new Currency($data['currency']),
            new DateTime($data['date']),
            new DateTime($data['paymentdate']),
            new Language((int) $data['translation_language'] === 0 ? 'PL' : 'EN')
        );
    }

    private function getInvoiceItems(array $items): array
    {
        $invoiceItems = [];

        foreach ($items as $key => $item) {
            $invoiceItems[] = new WFirmaInvoiceItem(
              new Invoice\InvoiceItem\Name($item['name']),
              new Invoice\InvoiceItem\Price((int) ($item['price'] * 100)),
              new Invoice\InvoiceItem\ValueAddedTax(0),
              new Invoice\InvoiceItem\NumberOfUnits((int) $item['count'])
            );
        }

        return $invoiceItems;
    }
}
