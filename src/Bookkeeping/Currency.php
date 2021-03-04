<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping;

final class Currency
{
    private string $symbol;

    public function __construct(string $symbol)
    {
        $this->symbol = $symbol;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }
}
