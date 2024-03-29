<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\Condition;

use DateTimeImmutable;
use Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection\InvoiceCondition;

final class ExactDate implements InvoiceCondition
{
    private DateTimeImmutable $date;

    public function __construct(DateTimeImmutable $date)
    {
        $this->date = $date;
    }

    public function __toString(): string
    {
        return $this->date->format('Y-m-d');
    }
}
