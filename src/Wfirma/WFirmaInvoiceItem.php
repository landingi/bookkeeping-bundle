<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem;
use Landingi\BookkeepingBundle\Bookkeeping\Media;

final class WFirmaInvoiceItem extends InvoiceItem
{
    public function print(Media $media): Media
    {
        $content = $media->with('invoicecontent', '');

        $content->with('name', $this->name->toString());
        $content->with('unit', 'szt.');
        $content->with('count', $this->numberOfUnits->toString());
        $content->with('price', $this->price->toString());


        return $media;
    }
}
