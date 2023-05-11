<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Invoice\Collection;

interface Condition
{
    public function __toString(): string;
}
