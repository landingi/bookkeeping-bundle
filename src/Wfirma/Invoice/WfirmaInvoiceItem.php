<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma\Invoice;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Name;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\NumberOfUnits;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem\Price;
use Landingi\BookkeepingBundle\Bookkeeping\Media;
use Landingi\BookkeepingBundle\Wfirma\Invoice\InvoiceItem\WfirmaValueAddedTax;

final class WfirmaInvoiceItem extends InvoiceItem
{
    private string $vatId;

    /**
     * WfirmaValueAddedTax - to haksior bo wfirma jest zjebana.
     */
    public function __construct(Name $name, Price $price, WfirmaValueAddedTax $tax, NumberOfUnits $numberOfUnits)
    {
        $this->vatId = $tax->getIdentifier();
        parent::__construct($name, $price, $tax->getTax(), $numberOfUnits);
    }

    public function print(Media $media): Media
    {
        $content = $media->with('invoicecontent', '');
        $content->with('name', $this->name->toString());
        $content->with('unit', 'szt.');
        $content->with('count', $this->numberOfUnits->toString());
        $content->with('price', $this->price->toString());

        //NP id in wfirma is 230
        if (WfirmaValueAddedTax::NO_TAX === $this->vatId) {
            $content->with('vat', WfirmaValueAddedTax::NO_TAX);
        } elseif ($this->vatId) {
            $vatCode = $content->with('vat_code', '');
            $vatCode->with('id', $this->vatId);
        } else {
            $content->with('vat', (string) $this->tax->getRate());
        }

        return $media;
    }

    public function getVatId(): string
    {
        return $this->vatId;
    }
}
