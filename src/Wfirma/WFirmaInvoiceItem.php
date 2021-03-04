<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice\InvoiceItem;
use Landingi\BookkeepingBundle\Bookkeeping\Media;

final class WFirmaInvoiceItem extends InvoiceItem
{
    public function print(Media $media): Media
    {
        // TODO: Implement print() method.
    }
}
