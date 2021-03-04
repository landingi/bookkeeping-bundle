<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Wfirma;

use Landingi\BookkeepingBundle\Bookkeeping\Invoice;
use Landingi\BookkeepingBundle\Bookkeeping\Media;

final class WFirmaInvoice extends Invoice
{
    public function print(Media $media): Media
    {
        // TODO: Implement print() method.
    }
}
