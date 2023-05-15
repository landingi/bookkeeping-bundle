<?php

declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Collection;

interface CollectionCondition
{
    public function __toString(): string;
}
