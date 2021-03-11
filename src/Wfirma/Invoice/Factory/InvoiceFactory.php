<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice\Factory;

use DateTime;
use Landingi\BookkeepingBundle\Bookkeeping\Contractor;
use Landingi\BookkeepingBundle\Bookkeeping\Currency;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceSeries\InvoiceSeriesIdentifier;
use Landingi\BookkeepingBundle\Bookkeeping\Language;
use Landingi\BookkeepingBundle\Wfirma\Invoice\InvoiceItem\WfirmaValueAddedTax;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItem;
use Landingi\BookkeepingBundle\Wfirma\Invoice\WfirmaInvoiceItemCollection;
use Landingi\BookkeepingBundle\Wfirma\WfirmaInvoice;

final class InvoiceFactory
{
    public function getInvoiceFromApiData(array $data, Contractor $contractor): Invoice
    {
        return new WfirmaInvoice(
            new Invoice\InvoiceIdentifier($data['id']),
            new Invoice\InvoiceSeries(new InvoiceSeriesIdentifier($data['series']['id'])),
            new Invoice\InvoiceDescription($data['description']),
            new Invoice\InvoiceFullNumber($data['fullnumber']),
            new WfirmaInvoiceItemCollection($this->getInvoiceItems($data['invoicecontents'])),
            $contractor,
            new Currency($data['currency']),
            new DateTime($data['date']),
            new DateTime($data['paymentdate']),
            new Language(0 === (int) $data['translation_language'] ? 'PL' : 'EN')
        );
    }

    private function getInvoiceItems(array $items): array
    {
        $invoiceItems = [];

        foreach ($items as $key => $item) {
            $invoiceItems[] = new WfirmaInvoiceItem(
              new Invoice\InvoiceItem\Name($item['invoicecontent']['name']),
              new Invoice\InvoiceItem\Price((int) ($item['invoicecontent']['price'] * 100)),
              new WfirmaValueAddedTax(
                  (string) $item['invoicecontent']['vat_code']['id'],
                  new Invoice\InvoiceItem\ValueAddedTax(0)
              ),
              new Invoice\InvoiceItem\NumberOfUnits((int) $item['invoicecontent']['count'])
            );
        }

        return $invoiceItems;
    }
}
