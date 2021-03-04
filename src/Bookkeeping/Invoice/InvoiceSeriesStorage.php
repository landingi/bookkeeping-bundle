<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice;

interface InvoiceSeriesStorage
{
    public function getByIdentifier();
    public function getByName();
}
