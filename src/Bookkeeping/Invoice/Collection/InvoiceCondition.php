<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection;

use Landingi\BookkeepingBundle\Bookkeeping\Collection\CollectionCondition;

interface InvoiceCondition extends CollectionCondition
{
    public function __toString(): string;
}
