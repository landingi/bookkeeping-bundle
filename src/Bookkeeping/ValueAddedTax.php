<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

final class ValueAddedTax
{
    private int $rate;

    public function __construct(int $rate)
    {
        $this->rate = $rate;
    }

    public function getRate(): int
    {
        return $this->rate;
    }
}
