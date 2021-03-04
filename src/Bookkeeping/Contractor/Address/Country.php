<?php
declare(strict_types=1);

namespace Landingi\BookkeepingBundle\Bookkeeping\Contractor\Address;

final class Country
{
    private string $name;
    private string $alpha2Code;

    public function __construct(string $name, string $alpha2Code)
    {
        $this->name = $name;
        $this->alpha2Code = $alpha2Code;
    }

    public function getAlpha2Code(): string
    {
        return $this->alpha2Code;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
